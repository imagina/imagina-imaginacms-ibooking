<?php

namespace Modules\Ibooking\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Core\Icrud\Entities\CrudModel;

use Modules\Imeeting\Traits\Meetingable;

class ReservationItem extends CrudModel
{
    use Meetingable;

    protected $table = 'ibooking__reservation_items';
    
    protected $fillable = [
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
  
  public function reservation(){
    return $this->belongsTo(Reservation::class);
  }
  
}
