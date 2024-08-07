<?php

namespace Modules\Ibooking\Entities;

use Modules\Core\Icrud\Entities\CrudModel;
use Modules\Ifillable\Traits\isFillable;

class ReservationItem extends CrudModel
{
    use isFillable;

    public $transformer = 'Modules\Ibooking\Transformers\ReservationItemTransformer';

    public $repository = 'Modules\Ibooking\Repositories\ReservationItemRepository';

    public $requestValidation = [
        'create' => 'Modules\Ibooking\Http\Requests\CreateReservationItemRequest',
        'update' => 'Modules\Ibooking\Http\Requests\UpdateReservationItemRequest',
    ];

    public $modelRelations = [
        'reservation' => 'belongsTo',
        'service' => 'belongsTo',
        'resource' => 'belongsTo',
    ];

    protected $table = 'ibooking__reservation_items';

    protected $fillable = [
        'reservation_id',
        'service_id',
        'resource_id',
        'category_id',
        'category_title',
        'service_title',
        'resource_title',
        'price',
        'start_date',
        'end_date',
        'customer_id',
        'entity_type',
        'entity_id',
        'status',
    ];

    protected $with = ['fields'];

    //============== RELATIONS ==============//

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }

    public function customer()
    {
        $driver = config('asgard.user.config.driver');

        return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User", 'customer_id');
    }

    //============== MUTATORS / ACCESORS ==============//

  public function getStatusNameAttribute()
  {

    $status = new Status();
    return $status->get($this->status);

  }

  /**
   * Make Notificable Params | to Trait
   * @param $event (created|updated|deleted)
   */
  public function isNotificableParams($event)
  {

    //Validation Event Update
    if($event=="updated"){
      //Validation Att Status Change
      if(!$this->wasChanged("status")){
        return null;
      }
    }

    //Get Emails and Broadcast
    $reservationService = app("Modules\Ibooking\Services\ReservationService");
    $result = $reservationService->getEmailsAndBroadcast($this->reservation);

    return [
      'created' => [
        "title" => trans('ibooking::reservations.messages.purchase reservation') . " #" . $this->reservation->id,
        "email" => $result['email'],
        "broadcast" => $result['broadcast'],
        "content" => "ibooking::emails.reservation",
        "layout" => "notification::emails.layouts.template-1",
        "extraParams" => [
          'reservation' => $this->reservation
        ],
      ],
      'updated' => [
        "title" => trans("ibooking::reservations.email.statusChanged.title"),
        "email" => $result['email'],
        "broadcast" => $result['broadcast'],
        "content" => "ibooking::emails.reservation",
        "layout" => "notification::emails.layouts.template-1",
        "extraParams" => [
          'reservation' => $this->reservation
        ],
      ],
    ];

  }
}
