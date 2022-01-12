<?php

namespace Modules\Ibooking\Events;

use Modules\Ibooking\Entities\Reservation;

class ReservationWasCreated
{
    
    
    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

}