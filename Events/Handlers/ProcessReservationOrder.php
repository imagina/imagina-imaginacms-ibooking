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

        \Log::info('Ibooking: Event - Process Reservation');

        $order = $event->order;
        //Order is Proccesed
        if($order->status_id==13){

            $reservationData = ['customer_id' => $order->customer_id,'items' => []];

            \Log::info('Ibooking: Event - Data: '.json_encode($reservationData));

            foreach($order->orderItems as $item){
                // Reservation Data
                $reservationData['items'][] = (array)$item->options->reservationItemData;

                $reservationRepository = app('Modules\Ibooking\Repositories\ReservationRepository');

                // Create Reservation
                $reservationRepository->create($reservationData);

                // Log
                $user = $order->customer;
                \Log::info("{$this->logtitle}Order Completed | Register reservation to user ID {$user->id}");

            }

        }// end If


    }// If handle



}
