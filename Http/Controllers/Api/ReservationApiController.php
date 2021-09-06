<?php

namespace Modules\Ibooking\Http\Controllers\Api;

use Modules\Core\Icrud\Controllers\BaseCrudController;
//Model Repository
use Modules\Ibooking\Repositories\ReservationRepository;
use Modules\Ibooking\Entities\Reservation;


class ReservationApiController extends BaseCrudController
{

  public $model;
  public $modelRepository;

  public function __construct(Reservation $model,ReservationRepository $modelRepository)
  {
    $this->model = $model;
    $this->modelRepository = $modelRepository;
  }
  
 
}