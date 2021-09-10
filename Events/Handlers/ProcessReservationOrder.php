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
        $order = $event->order;
        //Order is Proccesed
        if($order->status_id==13){

            $reservationData = ['customer_id' => $order->customer_id,'items' => []];


            foreach($order->orderItems as $item){

                // Reservation Data
                $reservationData['items'][] = json_decode($item->options)->reservationItemData;

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
