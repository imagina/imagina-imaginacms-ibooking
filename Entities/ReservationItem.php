<?php

namespace Modules\Ibooking\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Icrud\Entities\CrudModel;

use Modules\Ibooking\Traits\WithMeeting;

class ReservationItem extends CrudModel
{
  use WithMeeting;

  public $transformer = 'Modules\Ibooking\Transformers\ReservationItemTransformer';
  public $requestValidation = [
    'create' => 'Modules\Ibooking\Http\Requests\CreateReservationItemRequest',
    'update' => 'Modules\Ibooking\Http\Requests\UpdateReservationItemRequest',
  ];


  public $modelRelations = [
    'reservation' => 'belongsTo',
    'service' => 'belongsTo',
    'resource' => 'belongsTo'
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
    'end_date'
  ];

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
}
