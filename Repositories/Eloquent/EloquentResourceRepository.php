<?php

namespace Modules\Ibooking\Repositories\Eloquent;

use Modules\Core\Icrud\Repositories\Eloquent\EloquentCrudRepository;
use Modules\Ibooking\Repositories\ResourceRepository;

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
}
