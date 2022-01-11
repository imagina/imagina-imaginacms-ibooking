<?php

namespace Modules\Ibooking\Events\Handlers;

class ProcessReservationOrder
{

    private $logtitle;

    public function __construct()
    {
        $this->logtitle = '[IBOOKING-RESERVATION]::';
    }

    public function handle($event)
    {

        \Log::info('Ibooking: Events|Handlers|ProcessReservationOrder');

        $order = $event->order;
        //Order is Proccesed
        if($order->status_id==13){

            // Get Customer Id from Order
            $reservationData = ['customer_id' => $order->customer_id,'items' => []];

            \Log::info('Ibooking: Events|Handlers|ProcessReservationOrder|ReservationData: '.json_encode($reservationData));

            foreach($order->orderItems as $item){
                // Reservation Data
                $reservationData['items'][] = (array)$item->options->reservationItemData;

                $reservationRepository = app('Modules\Ibooking\Repositories\ReservationRepository');

                // Create Reservation and ReservationItem
                $reservationRepository->create($reservationData);

                // Log
                $user = $order->customer;
                \Log::info("{$this->logtitle}Order Completed | Register reservation to user ID {$user->id}");

            }

        }// end If


    }// If handle



}
