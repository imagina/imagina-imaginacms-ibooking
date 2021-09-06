<?php

namespace Modules\Ibooking\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Icrud\Entities\CrudModel;

class Reservation extends CrudModel
{
  

  public $transformer = 'Modules\Ibooking\Transformers\ReservationTransformer';
  public $requestValidation = [
    'create' => 'Modules\Ibooking\Http\Requests\CreateReservationRequest',
    'update' => 'Modules\Ibooking\Http\Requests\UpdateReservationRequest',
  ];

  protected $table = 'ibooking__reservations';
  protected $casts = ['options' => 'array'];
  protected $fillable = [
    'customer_id',
    'status',
    'options'
  ];

  public function items()
  {
    return $this->hasMany(ReservationItems::class);
  }

}
