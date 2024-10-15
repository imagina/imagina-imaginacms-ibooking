<?php

namespace Modules\Ibooking\Repositories\Cache;

use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;
use Modules\Ibooking\Repositories\ReservationItemRepository;

class CacheReservationItemDecorator extends BaseCacheCrudDecorator implements ReservationItemRepository
{
    public function __construct(ReservationItemRepository $reservationitem)
    {
        parent::__construct();
        $this->entityName = 'ibooking.reservationitems';
        $this->tags = ["ibooking.reservations"];
        $this->repository = $reservationitem;
    }
}
