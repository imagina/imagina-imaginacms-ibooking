<?php


namespace Modules\Ibooking\Services;

//Events
use Modules\Ibooking\Events\ReservationWasCreated;

class ReservationService
{
  
 
  
  /**
  * @return cart service created
  */
  public function createCheckoutCart($items,$reservation=null){
  
    $cartService = app("Modules\Icommerce\Services\CartService");
    $products = [];

    // Add Reservation Item for ItemS
    foreach ($items as $item) {
        
        $reservationItemData = $this->createReservationItemData($item);

        // Set Products to Cart
        $products[] = [
          "id" => $reservationItemData['service']->product->id, // OJO - getProductAttribute - Version que ya estaba
          "quantity" => 1,
          "options" => ['reservationId'=>$reservation->id,'reservationItemData' => $reservationItemData['reservationItem']]
        ];
   
        //\Log::info("Ibooking: Services|CheckoutService|Create: ".json_encode($products));
      }

      // Create the Cart
      $cart = $cartService->create(["products" => $products]);

      return $cartService;
  }

  /**
  * @return reservation
  */
  public function createReservation($data){

    // Get Customer Id if exist
    if(isset($data['customer_id']))
      $reservationData = ['customer_id' => $data['customer_id'],'items' => []];

    // If no exist is 0 (Pending)
    $reservationData['status'] = (int)setting('ibooking::reservationStatusDefault',null,0);
    
    // Add Reservation Item for ItemS
    foreach ($data['items'] as $item) {
      $reservationItemData = $this->createReservationItemData($item);
      $reservationData['items'][] = $reservationItemData['reservationItem'];
    }

    // Extra Data in Options
    // If the customer_id does not exist, the email must come in the request
    if(!isset($data['customer_id']) || empty($data['customer_id'])){
      $options['email'] = $data['email']; // From Request
      // Save all
      $reservationData['options'] = $options;
    }

    //\Log::info("Ibooking: Services|ReservationService|Create|reservationData ".json_encode($reservationData));
    $reservationRepository = app('Modules\Ibooking\Repositories\ReservationRepository');

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
          $reservationItem['organization_id'] = $resource->organization_id ?? null;
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
