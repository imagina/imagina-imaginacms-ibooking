<?php

namespace Modules\Ibooking\Entities;


class Status
{
    const PENDING = 0;
    const APPROVED = 1;
    const CANCELED = 2;
    
    private $statuses = [];

    public function __construct()
    {
        $this->statuses = [
            self::PENDING => trans('ibooking::reservations.status.pending'),
            self::APPROVED => trans('ibooking::reservations.status.approved'),
            self::CANCELED => trans('ibooking::reservations.status.canceled'),
        ];
    }

    public function lists()
    {
        return $this->statuses;
    }

   
    public function get($statusId)
    {
        if (isset($this->statuses[$statusId])) {
            return $this->statuses[$statusId];
        }

        return $this->statuses[self::PENDING];
    }
    
}
