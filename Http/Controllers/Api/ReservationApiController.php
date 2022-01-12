<?php

namespace Modules\Ibooking\Http\Controllers\Api;

use Modules\Core\Icrud\Controllers\BaseCrudController;

//Model Repository
use Modules\Ibooking\Repositories\ReservationRepository;
use Modules\Ibooking\Entities\Reservation;

use Illuminate\Http\Request;

class ReservationApiController extends BaseCrudController
{

  public $model;
  public $modelRepository;

  public function __construct(Reservation $model, ReservationRepository $modelRepository)
  {
    $this->model = $model;
    $this->modelRepository = $modelRepository;
  }

  /**
   * CREATE A ITEM
   *
   * @param Request $request
   * @return mixed
   */
  public function create(Request $request)
  {
    \DB::beginTransaction();
    try {
      //Get model data
      $modelData = $request->input('attributes') ?? [];

      //Validate Request
      if (isset($this->model->requestValidation['create'])) {
        $this->validateRequestApi(new $this->model->requestValidation['create']($modelData));
      }


      //\Log::info("Ibooking: ReservationApiController|Create|ModelDataRequest: ".json_encode($modelData['items']));

      //IF REQUIRE PAYMENT
      if(is_module_enabled('Icommerce') && setting('ibooking::reservationWithPayment',null, false)){

        $checkoutCart = app("Modules\Ibooking\Services\CheckoutService")->create($modelData['items']);

      }else{

        // Create Reservation
        // Create Notification

      }


      \DB::commit(); //Commit to Data Base
    } catch (\Exception $e) {

      \Log::error('Ibooking: ReservationApiController|Create|Message: '.$e->getMessage().' | FILE: '.$e->getFile().' | LINE: '.$e->getLine());

      \DB::rollback();//Rollback to Data Base
      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }
    //Return response
    return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
  }
}
