<?php

namespace Modules\Ibooking\Entities;

use Modules\Core\Icrud\Entities\CrudModel;
use Modules\Ibooking\Traits\WithItems;
use Modules\Iwallet\Traits\isTransactionable;
use Stancl\Tenancy\Database\Concerns\BelongsToTenant;
use Modules\Iwallet\Entities\Type;

class Reservation extends CrudModel
{
  use WithItems, isTransactionable;

  // Event to update status itemsssss
  //use BelongsToTenant;

  public $transformer = 'Modules\Ibooking\Transformers\ReservationTransformer';

  public $repository = 'Modules\Ibooking\Repositories\ReservationRepository';

  public $requestValidation = [
    'create' => 'Modules\Ibooking\Http\Requests\CreateReservationRequest',
    'update' => 'Modules\Ibooking\Http\Requests\UpdateReservationRequest',
  ];

  public $modelRelations = [
    'items' => 'hasMany',
  ];

  protected $table = 'ibooking__reservations';

  protected $casts = ['options' => 'array'];

  protected $fillable = [
    'customer_id',
    'status',
    'resource_id',
    'resource_title',
    'start_date',
    'end_date',
    'options',
  ];

  //============== RELATIONS ==============//

  public function items()
  {
    return $this->hasMany(ReservationItem::class);
  }

  public function customer()
  {
    $driver = config('asgard.user.config.driver');

    return $this->belongsTo("Modules\\User\\Entities\\{$driver}\\User", 'customer_id');
  }

  //============== MUTATORS / ACCESORS ==============//

  public function setOptionsAttribute($value)
  {
    $this->attributes['options'] = json_encode($value);
  }

  public function getOptionsAttribute($value)
  {
    return json_decode($value);
  }

  public function getStatusNameAttribute()
  {
    $status = new Status();

    return $status->get($this->status);
  }

  public function getStatusModelAttribute()
  {
    $status = new Status();
    return $status->show($this->status);
  }

  public function getHumanShiftTimeAttribute()
  {
    return humanizeDuration($this->start_date, $this->end_date);
  }

  public function resource()
  {
    return $this->belongsTo(Resource::class);
  }

  public function getTransactionData()
  {
    $reservationPrice = 0;
    $response = [];
    $resourceTypeValueClass = new ResourceValueType();

    //Define transaction data by service
    foreach ($this->items as $item) {
      $reservationPrice += $item->price;
      if (isset($item->options['resourceValueType']) && isset($item->options['resourceValue'])) {
        //calculate the amount for the resource
        $amount = $item->options['resourceValueType'] == ResourceValueType::PRICE ? $item->options['resourceValue'] :
          (($item->price * $item->options['resourceValue']) / 100);
        //Get the resource value type model
        $resourceValueTypeTitle = $resourceTypeValueClass->show($item->options['resourceValueType'])["title"];
        //Instance the transaction data
        $response[] = [
          "amount" => $amount * -1,
          "comment" => "Booking:{$this->id}|{$item->service_title} / " .
            trans("ibooking::common.customer") . ":{$this->customer->id}|{$this->customer->first_name} {$this->customer->last_name} / " .
            $resourceValueTypeTitle . "|{$item->options['resourceValue']}",
          "pocket" => [
            "entity_type" => Resource::class,
            "entity_id" => $this->resource_id,
            "title" => "Booking | " . $this->resource->title,
            "type_id" => Type::DEBT
          ]
        ];
      }
    }

    //Include the reservation transaction data
    if ($reservationPrice) $response[] = [
      "amount" => $reservationPrice,
      "comment" => "Booking:{$this->id}|" . $this->items->pluck('service_title')->join(',') . " / " .
        trans("ibooking::common.customer") . ":{$this->customer->id}|{$this->customer->first_name} {$this->customer->last_name} / " .
        trans("ibooking::common.resource") . "|{$this->resource->title}",
    ];

    //Response
    return $response;
  }
}
