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

      //TODO
      //IF NOT REQUIRE PAYMENT
      //CREATE RESERVATION


      //IF REQUIRE PAYMENT
      $cartService = app("Modules\Icommerce\Services\CartService");
      $products = [];

      foreach ($modelData['items'] as $item) {
        $reservationItem = [];

        if (isset($item['service_id'])) {
          $service = app("Modules\Ibooking\Repositories\ServiceRepository")->find($item['service_id']);
          $reservationItem['service_id'] = $service->id;
          $reservationItem['service_title'] = $service->title;
        }

        if (isset($item['resource_id'])) {
          $resource = app("Modules\Ibooking\Repositories\ResourceRepository")->find($item['resource_id']);
          $reservationItem['resource_id'] = $resource->id;
          $reservationItem['resource_title'] = $resource->title;
        }

        if (isset($item['category_id'])) {
          $category = app("Modules\Ibooking\Repositories\CategoryRepository")->find($item['category_id']);
          $reservationItem['category_id'] = $category->id;
          $reservationItem['category_title'] = $category->title;
        }

        if (isset($item['start_date'])) $reservationItem['start_date'] = $item['start_date'];

        if (isset($item['end_date'])) $reservationItem['end_date'] = $item['end_date'];

        // Set Products to Cart
        $products[] = [
          "id" => $service->product->id,
          "quantity" => 1,
          "options" => ['reservationItemData' => $reservationItem] // Duda - This is saved in the order - need to create reservation after payment
        ];

        \Log::info("Ibooking: Reservation Api Controller - Create - Products: ".json_encode($products));
      }

      // Create the Cart
      $cartService->create(["products" => $products]);
      \DB::commit(); //Commit to Data Base
    } catch (\Exception $e) {
      \DB::rollback();//Rollback to Data Base
      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }
    //Return response
    return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
  }
}
