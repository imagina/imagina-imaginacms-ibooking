<?php

namespace Modules\Ibooking\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Icrud\Entities\CrudModel;

use Modules\Ibooking\Traits\WithMeeting;

class ReservationItem extends CrudModel
{
    use WithMeeting;

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
      'withMeeting'
    ];
  
  public function reservation(){
    return $this->belongsTo(Reservation::class);
  }

  public function service()
  {
    return $this->belongsTo(Service::class);
  }
  
}
