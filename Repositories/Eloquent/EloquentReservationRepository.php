<?php

namespace Modules\Ibooking\Repositories\Eloquent;

use Modules\Core\Icrud\Repositories\Eloquent\EloquentCrudRepository;
use Modules\Ibooking\Entities\Status;
use Modules\Ibooking\Repositories\ReservationRepository;
use Modules\Ibooking\Entities\ReservationItem;
use Carbon\Carbon;

class EloquentReservationRepository extends EloquentCrudRepository implements ReservationRepository
{
  /**
   * Filter name to replace
   *
   * @var array
   */
  protected $replaceFilters = ['resourceId'];

  /**
   * Filter query
   *
   * @return mixed
   */
  public function filterQuery($query, $filter, $params)
  {
    /**
     * Note: Add filter name to replaceFilters attribute to replace it
     *
     * Example filter Query
     * if (isset($filter->status)) $query->where('status', $filter->status);
     */
    //Filter by resource
    if (isset($filter->resourceId)) {
      $resorceId = is_array($filter->resourceId) ? $filter->resourceId : [$filter->resourceId];
      if (count($resorceId)) $query->whereIn('resource_id', $resorceId);
    }

    //Filter by service
    if (isset($filter->serviceId)) {
      $serviceId = is_array($filter->serviceId) ? $filter->serviceId : [$filter->serviceId];
      if (count($serviceId)) {
        $query->whereHas('items', function ($query) use ($serviceId) {
          $query->whereIn('service_id', $serviceId);
        });
      }
    }
    //Filter by category
    if (isset($filter->categoryId)) {
      $categoryId = is_array($filter->categoryId) ? $filter->categoryId : [$filter->categoryId];
      if (count($categoryId)) {
        $query->whereHas('items', function ($query) use ($categoryId) {
          $query->whereIn('category_id', $categoryId);
        });
      }
    }

    //Response
    return $query;
  }

  public function afterUpdate(&$model, &$data)
  {
    //Change the start/end dates with the status change
    $boolValue = (bool)setting('ibooking::allowChangeAutomaticDates', null, false);
    if ($boolValue) {
      $dataToChange = $model->getChanges();
      $status = $dataToChange['status'] ?? null;
      if ($status == Status::INPROGRESS) $data['start_date'] = now(); // In Progress State
      else if ($status == Status::COMPLETED) $data['end_date'] = now(); // Completed State

      if ($status == Status::INPROGRESS || $status == Status::COMPLETED) $model->update((array)$data);
    }

    // Changes the reservation items
    if (isset($data['change_services'])) {
      $servicesRepository = app('Modules\Ibooking\Repositories\ServiceRepository');
      $services = $servicesRepository->getItemsBy(json_decode(json_encode([
        'filter' => ['id' => $data['change_services']],
        'include' => ['category'],
      ])));
      $newReservationItems = [];
      foreach ($services as $service) {
        $newReservationItems[] = new ReservationItem([
          'reservation_id' => $model->id,
          'service_id' => $service->id,
          'category_id' => $service->category_id,
          'category_title' => $service->category->title,
          'service_title' => $service->title,
          'price' => $service->price,
          'customer_id' => $model->customer_id,
          'shift_time' => $service->shift_time,
          'options' => $service->options,
          'created_at' => now(),
          'updated_at' => now()
        ]);
      }

      // Remove current items (ensure all related items are deleted)
      ReservationItem::where('reservation_id', $model->id)->forceDelete();
      //Insert the new items
      $model->items()->saveMany($newReservationItems);
    }

    //update resource title
    $resource = app("Modules\Ibooking\Repositories\ResourceRepository")->find($model->resource_id);
    $model->update(['resource_title' => $resource->title]);
  }

  public function getDashboard($params)
  {
    $response = [];
    // Get the current application language
    $currentLanguage = \App::getLocale();
    //Get filters
    $filter = $params->filter;
    // get date range
    $startDate = $filter->date->from ?? Carbon::today();
    $endDate = $filter->date->to ?? Carbon::today();

    //------------ Get the reservation by category
    $response['reservationsByCategory'] = $totalByCategory = ReservationItem::select('category_title')
      ->selectRaw('SUM(price) as totalPrice, COUNT(DISTINCT reservation_id) as quantity')
      ->whereHas('reservation', function ($query) use ($startDate, $endDate) {
        $query->whereDate('start_date', '>=', $startDate)
          ->whereDate('start_date', '<=', $endDate)
          ->whereNull('deleted_at');
      })
      ->groupBy('category_title')
      ->get()
      ->map(function ($item) {
        return [
          'category' => $item->category_title,
          'quantity' => $item->quantity,
          'total' => $item->totalPrice,
        ];
      });

    //------------ Get reservations information
    $response['reservations'] = [
      'quantity' => $response['reservationsByCategory']->sum('quantity'),
      'total' => $response['reservationsByCategory']->sum('total'),
    ];

    //------------ Get services information
    $response['services'] = ReservationItem::select(
      'service_title',
      \DB::raw('count(*) as quantity'),
      \DB::raw('sum(price) as total'))
      ->whereHas('reservation', function ($query) use ($startDate, $endDate) {
        $query->whereDate('start_date', '>=', $startDate)
          ->whereDate('start_date', '<=', $endDate)
          ->whereNull('deleted_at');
      })
      ->groupBy('service_title')
      ->get()
      ->map(function ($item) {
        return [
          'service' => $item->service_title,
          'quantity' => $item->quantity,
          'total' => $item->total,
        ];
      })->toArray();

    //------------ Get services by resource
    $response["serviceByResource"] = ReservationItem::select(
      'ibooking__reservations.resource_id',
      'ibooking__reservation_items.service_title',
      'ibooking__resource_translations.title as resource_title',
      \DB::raw('count(*) as quantity'),
      \DB::raw('sum(resource_price) as total')
    )
      ->join('ibooking__reservations', 'ibooking__reservation_items.reservation_id', '=', 'ibooking__reservations.id')
      ->join('ibooking__resource_translations', function ($join) use ($currentLanguage) {
        $join->on('ibooking__reservations.resource_id', '=', 'ibooking__resource_translations.resource_id')
          ->where('ibooking__resource_translations.locale', '=', $currentLanguage);
      })
      ->whereDate('ibooking__reservations.start_date', '>=', $startDate)
      ->whereDate('ibooking__reservations.start_date', '<=', $endDate)
      ->whereNull('ibooking__reservations.deleted_at')
      ->groupBy(
        'ibooking__reservations.resource_id',
        'ibooking__reservation_items.service_title',
        'ibooking__resource_translations.title'
      )
      ->get()
      ->groupBy('resource_title')
      ->map(function ($items) {
        return $items->map(function ($item) {
          return [
            'service' => $item->service_title,
            'quantity' => $item->quantity,
            'total' => $item->total,
          ];
        });
      });

    //------------ Get Reservations by resource
    $response["reservationsByResource"] = $this->model->selectRaw(
      'resource_title, count(*) as quantity'
    )
      ->whereDate('start_date', '>=', $startDate)
      ->whereDate('start_date', '<=', $endDate)
      ->whereNull('deleted_at')
      ->groupBy('resource_title')
      ->get()
      ->mapWithKeys(function ($item) {
        return [
          $item->resource_title => [
            'resource_title' => $item->resource_title,
            'quantity' => $item->quantity,
          ],
        ];
      });

    //Response
    return $response;
  }
}
