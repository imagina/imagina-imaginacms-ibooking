<?php

namespace Modules\Ibooking\Http\Controllers\Api;

use Modules\Core\Icrud\Controllers\BaseCrudController;
//Model Repository
use Modules\Ibooking\Repositories\ReservationItemRepository;
//Model Requests
use Modules\Ibooking\Http\Requests\CreateReservationItemRequest;
use Modules\Ibooking\Http\Requests\UpdateReservationItemRequest;
//Transformer
use Modules\Ibooking\Transformers\ReservationItemTransformer;

class ReservationItemApiController extends BaseCrudController
{
  public $modelRepository;

  public function __construct(ReservationItemRepository $modelRepository)
  {
    $this->modelRepository = $modelRepository;
  }
  
  /**
   * Return request to create model
   *
   * @param $modelData
   * @return false
   */
  public function modelCreateRequest($modelData)
  {
    return new CreateReservationItemRequest($modelData);
  }

  /**
   * Return request to create model
   *
   * @param $modelData
   * @return false
   */
  public function modelUpdateRequest($modelData)
  {
    return new UpdateReservationItemRequest($modelData);
  }

  /**
   * Return model collection transformer
   *
   * @param $data
   * @return mixed
   */
  public function modelCollectionTransformer($data)
  {
    return ReservationItemTransformer::collection($data);
  }

  /**
   * Return model transformer
   *
   * @param $data
   * @return mixed
   */
  public function modelTransformer($data)
  {
    return new ReservationItemTransformer($data);
  }
}