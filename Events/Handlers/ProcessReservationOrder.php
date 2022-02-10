<?php

namespace Modules\Ibooking\Events\Handlers;

use Modules\Ibooking\Repositories\ReservationRepository;
use Modules\Ibooking\Events\ReservationWasCreated;

class ProcessReservationOrder
{

    public $reservationRepository;

    public function __construct(ReservationRepository $reservationRepository)
    {
      $this->reservationRepository = $reservationRepository; 
    }

    public function handle($event)
    {

        \Log::info('Ibooking: Events|Handlers|ProcessReservationOrder');

        $order = $event->order;
        //Order is Proccesed
        if($order->status_id==13){

            // Get Reservation Id From option in Order Item
            $reservationId = null;
            foreach($order->orderItems as $item){
                $reservationId = $item->options->reservationId;
                break;
            }

            // Update Status Reservation
            $reservation = $this->reservationRepository->updateBy($reservationId, [
              "status" => 1 //Approved
            ],null);

            // Check and create meeting for each item
            foreach ($reservation->items as $key => $item) {
               $item->createMeeting($item);
            }

        }// end If


    }// If handle



}
