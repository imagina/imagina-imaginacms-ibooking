<?php

namespace Modules\Ibooking\Repositories\Eloquent;

use Modules\Core\Icrud\Repositories\Eloquent\EloquentCrudRepository;
use Modules\Ibooking\Repositories\ReservationRepository;

class EloquentReservationRepository extends EloquentCrudRepository implements ReservationRepository
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
    if (isset($filter->resourceId)) {
      $resorceId = is_array($filter->resourceId) ? $filter->resourceId : [$filter->resourceId];
      if (count($resorceId)) {
        $query->whereHas('items', function ($query) use ($resorceId) {
          $query->whereIn('resource_id', $resorceId);
        });
      }
    }

    if (isset($filter->serviceId)) {
      $serviceId = is_array($filter->serviceId) ? $filter->serviceId : [$filter->serviceId];
      if (count($serviceId)) {
        $query->whereHas('items', function ($query) use ($serviceId) {
          $query->whereIn('service_id', $serviceId);
        });
      }
    }

    //Response
    return $query;
  }

  public function beforeUpdate(&$data)
  {
    $boolValue = (bool)setting('ibooking::allowChangeAutomaticDates', null, false);
    if ($boolValue)
    {
      if($data['status'] == 3) $data['start_date'] = now(); // In Progress State
      else if($data['status'] == 4) $data['end_date'] = now(); // Completed State
    }
  }
}
