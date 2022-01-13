<?php


namespace Modules\Ibooking\Services;

//Events
use Modules\Ibooking\Events\ReservationWasCreated;

class ReservationService
{
  
 
  
  /**
  * @return cart service created
  */
  public function createCheckoutCart($items){
  
    $cartService = app("Modules\Icommerce\Services\CartService");
    $products = [];

    // Add Reservation Item for ItemS
    foreach ($items as $item) {
        
        $reservationItemData = $this->createReservationItemData($item);

        // Set Products to Cart
        $products[] = [
          "id" => $reservationItemData['service']->product->id, // OJO - getProductAttribute - Version que ya estaba
          "quantity" => 1,
          "options" => ['reservationItemData' => $reservationItemData['reservationItem']]
        ];

        //\Log::info("Ibooking: Services|CheckoutService|Create: ".json_encode($products));
      }

      // Create the Cart
      $cartService->create(["products" => $products]);

      return $cartService;
  }

  /**
  * @return reservation
  */
  public function createReservation($data){


    // Add Reservation Item for ItemS
    foreach ($data['items'] as $item) {
      $reservationItemData = $this->createReservationItemData($item);
      $reservationData['items'][] = $reservationItemData['reservationItem'];
    }

    $reservationRepository = app('Modules\Ibooking\Repositories\ReservationRepository');

    //Data testing
    $data['email'] = "wavutes@gmail.com"; 
    $data['customer_id'] = null;

    // Extra Data in Options
    // TODO CHANGE - Define that the "extra data" comes in an array called "form" from Frontend
    if(!isset($data['customer_id']) || empty($data['customer_id'])){
      $options['email'] = $data['email'];
      // Save all
      $reservationData['options'] = $options;
    }
   
    // Create Reservation and ReservationItem
    $reservation = $reservationRepository->create($reservationData);

    // Send Email and Notification Iadmin
    event(new ReservationWasCreated($reservation));

    return $reservation;

  }

  /**
  * Get data from each item and create one array with the information 
  * @return Array - [service,reservationItem]
  */
  public function createReservationItemData($item){

      $reservationItem = [];
      $response = [];

      if (isset($item['service_id'])) {
          $service = app("Modules\Ibooking\Repositories\ServiceRepository")->find($item['service_id']);
          $reservationItem['service_id'] = $service->id;
          $reservationItem['service_title'] = $service->title;
          $reservationItem['price'] = $service->price;

          // Added service
          $response['service'] = $service;
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

      $response['reservationItem'] = $reservationItem;

      return $response;

  }
  
  
  
  
}
