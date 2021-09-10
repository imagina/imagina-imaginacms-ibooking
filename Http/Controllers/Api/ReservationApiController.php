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

  public function __construct(Reservation $model,ReservationRepository $modelRepository)
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
    
    try {
      
      $data = $request->input('attributes') ?? [];//Get data
           
      //TODO 
      //IF NOT REQUIRE PAYMENT 
      //CREATE RESERVATION


      //IF REQUIRE PAYMENT
      $cartService = app("Modules\Icommerce\Services\CartService");
      $extraData = [];

      if(isset($data['serviceId'])){
        $service = app("Modules\Ibooking\Repositories\ServiceRepository")->find($data['serviceId']);
        $extraData['service'] = [
          'service_id' => $service->id,
          'service_title' => $service->title
        ];
      }

      if(isset($data['resourceId'])){
        $resource = app("Modules\Ibooking\Repositories\ResourceRepository")->find($data['resourceId']);
        $extraData['resource'] = [
          'resource_id' => $resource->id,
          'resource_title' => $resource->title
        ];
      }

      if(isset($data['categoryId'])){
        $resource = app("Modules\Ibooking\Repositories\CategoryRepository")->find($data['categoryId']);
        $extraData['category'] = [
          'category_id' => $category->id,
          'category_title' => $category->title
        ];
      }

      if(isset($data['startDate']))
        $extraData['startDate'] = $data['startDate'];

      if(isset($data['endDate']))
        $extraData['endDate'] = $data['endDate'];

      // Set Products to Cart
      $products =   [[
        "id" => $service->product->id,
        "quantity" => 1,
        "options" => $extraData // Duda - This is saved in the order - need to create reservation after payment
      ]];

      // Create the Cart
      $cartService->create([
        "products" => $products
      ]);
      

    } catch (\Exception $e) {
      

      $status = $this->getStatusError($e->getCode());
      $response = ["errors" => $e->getMessage()];
    }
    //Return response
    return response()->json($response ?? ["data" => "Request successful"], $status ?? 200);
  }
  
 
}