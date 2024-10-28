<?php

namespace Modules\Ibooking\Repositories\Eloquent;

use Modules\Core\Icrud\Repositories\Eloquent\EloquentCrudRepository;
use Modules\Ibooking\Repositories\ResourceRepository;
use Modules\Ibooking\Entities\Reservation;
use Modules\Ibooking\Entities\ReservationItem;
use Modules\Ibooking\Entities\Status;
use Carbon\Carbon;

class EloquentResourceRepository extends EloquentCrudRepository implements ResourceRepository
{
  /**
   * Filter name to replace
   *
   * @var array
   */
  protected $replaceFilters = [];

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
    if (isset($filter->serviceId)) {
      $serviceId = (array)$filter->serviceId;
      if (count($serviceId)) {
        $query->whereHas('services', function ($q) use ($serviceId) {
          $q->whereIn('ibooking__service_resource.service_id', $serviceId);
        });
      }
    }

    //Response
    return $query;
  }

  public function getDashboard($params)
  {
    $response = [];
    //Get filters
    $filter = $params->filter;
    // get date range
    $startDate = $filter->date->from ?? Carbon::today();
    $endDate = $filter->date->to ?? Carbon::today();

    //------------ Get Reservations sumary
    $response["reservations"] = [
      "description" => trans('ibooking::common.reportOfCompleted'),
      "data" => Reservation::selectRaw(
        'COUNT(DISTINCT ibooking__reservations.id) as quantity, SUM(ibooking__reservation_items.resource_price) as total'
      )
        ->join('ibooking__reservation_items', 'ibooking__reservation_items.reservation_id', '=', 'ibooking__reservations.id')
        ->whereDate('ibooking__reservations.start_date', '>=', $startDate)
        ->whereDate('ibooking__reservations.start_date', '<=', $endDate)
        ->whereNull('ibooking__reservations.deleted_at')
        ->where('ibooking__reservations.status', Status::COMPLETED)
        ->whereHas('resource', function ($query) {
          $query->where('assigned_to_id', \Auth::id());
        })
        ->first()
    ];

    //------------ Get Reservations by resource
    $response["services"] = [
      "description" => trans('ibooking::common.reportOfCompleted'),
      "data" => ReservationItem::select(
        'ibooking__reservation_items.service_title as service',
        \DB::raw('COUNT(ibooking__reservation_items.id) as quantity'),
        \DB::raw('SUM(ibooking__reservation_items.resource_price) as total')
      )
        ->join('ibooking__reservations', 'ibooking__reservation_items.reservation_id', '=', 'ibooking__reservations.id')
        ->whereDate('ibooking__reservations.start_date', '>=', $startDate)
        ->whereDate('ibooking__reservations.start_date', '<=', $endDate)
        ->whereNull('ibooking__reservations.deleted_at')
        ->where('ibooking__reservations.status', Status::COMPLETED)
        ->whereHas('reservation.resource', function ($query) {
          $query->where('assigned_to_id', \Auth::id());
        })
        ->groupBy('ibooking__reservation_items.service_title')
        ->get()
    ];

    //Response
    return $response;
  }
}
