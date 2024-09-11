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

    if (isset($filter->orderByItemsDate)) {
      $query->join(\DB::raw('(SELECT reservation_id, MIN(start_date) as min_start_date FROM ibooking__reservation_items GROUP BY reservation_id) as sub'), 'ibooking__reservations.id', '=', 'sub.reservation_id')
        ->orderBy('sub.min_start_date')
        ->select('ibooking__reservations.*');
    }

    //Response
    return $query;
  }
}
