<?php

namespace Modules\Ibooking\Events\Handlers;

use Illuminate\Contracts\Mail\Mailer;
use Modules\User\Entities\Sentinel\User;
use Modules\Notification\Services\Notification;
//use Modules\Icommerce\Emails\Order;

class SendReservation
{
  
  /**
   * @var Mailer
   */
  private $mail;
  private $setting;
  private $notification;
  public $notificationService;
  
  public function __construct(Mailer $mail, Notification $notification)
  {
    $this->mail = $mail;
    $this->setting = app('Modules\Setting\Contracts\Setting');
    $this->notification = $notification;
    $this->notificationService = app("Modules\Notification\Services\Inotification");
    
  }
  
  public function handle($event)
  {

    try {

      $reservation = $event->reservation;
      
      //\Log::info("Ibooking: Events|Handler|SendReservation|ReservationId: ".$reservation->id);
      //\Log::info("Ibooking: Events|Handler|SendReservation|Notification: ".$notification);

      //Subject
      $subject = trans('ibooking::reservations.messages.purchase reservation') . " #" . $reservation->id;

      // OJOOOOOO esto falta verificar
      $emailTo = "wavutes@gmail.com";

       //Send pusher notification
      $this->notificationService->to(
        [
          "email" => $emailTo,
          'broadcast' => 1 // Array Ids // OJOOOOOO esto nose si es obligatorio
        ]
      )->push([
        "title" => trans("ibooking::reservations.title.confirmation reservation"),
        "message" => $subject,
        "icon_class" => "fas fa-shopping-cart",
        "link" => url('/'),
        "content" => "ibooking::emails.reservation",
        "view" => "ibooking::emails.Reservation",
        "frontEvent" => [
          "name" => "ibooking.new.reservation",
        ],
        "setting" => ["saveInDatabase" => 1],
        "reservation" => $reservation
      ]);

      
    } catch (\Exception $e) {
     
      \Log::error('Ibooking: Events|Handler|SendReservation|Message: '.$e->getMessage().' | FILE: '.$e->getFile().' | LINE: '.$e->getLine());
    }

    
  }
  
  
}
