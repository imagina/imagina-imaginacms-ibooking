<?php

namespace Modules\Ibooking\Entities;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class ReservationItem extends Model
{
    use Translatable;

    protected $table = 'ibooking__reservation_items';
    public $translatedAttributes = [];
    protected $fillable = [
      'service_id',
      'resource_id',
      'category_id',
      'category_title',
      'service_title',
      'resource_title',
      'price'
    ];
  
  public function reservation(){
    return $this->belongsTo(Reservation::class);
  }
  
}
