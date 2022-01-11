<?php


namespace Modules\Ibooking\Services;


class CheckoutService
{
  
 
  
  /*
  *  Create Checkout Cart
  */
  public function create($items)
  {
  
    
    $cartService = app("Modules\Icommerce\Services\CartService");
    $products = [];

    // Add Reservation Item for ItemS
    foreach ($items as $item) {
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
          "id" => $service->product->id, // OJO - getProductAttribute
          "quantity" => 1,
          "options" => ['reservationItemData' => $reservationItem] // Duda - This is saved in the order - need to create reservation after payment
        ];

        //\Log::info("Ibooking: Services|CheckoutService|Create: ".json_encode($products));
      }

      // Create the Cart
      $cartService->create(["products" => $products]);

      return $cartService;
  }
  
  
  
  
}
