<?php

namespace Modules\Ibooking\Repositories\Cache;

use Modules\Ibooking\Repositories\ReservationItemRepository;
use Modules\Core\Icrud\Repositories\Cache\BaseCacheCrudDecorator;

class CacheReservationItemDecorator extends BaseCacheCrudDecorator implements ReservationItemRepository
{
    public function __construct(ReservationItemRepository $reservationitem)
    {
        parent::__construct();
        $this->entityName = 'ibooking.reservationitems';
        $this->repository = $reservationitem;
    }
}
