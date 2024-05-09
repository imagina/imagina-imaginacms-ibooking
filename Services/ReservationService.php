<?php

namespace Modules\Ibooking\Services;

//Events
use Modules\Ibooking\Events\ReservationWasCreated;

//Entities
use Modules\User\Entities\Sentinel\User;
use Modules\Iforms\Entities\Field;

class ReservationService
{

  public $log = "Ibooking::Services|ReservationService|";

  /**
   * @return cart service created
   */
  public function createCheckoutCart($data, $reservation = null)
  {

    $cartService = app("Modules\Icommerce\Services\CartService");
    $products = [];
    $items = $data['items'];

    // Add Reservation Item for ItemS
    foreach ($items as $item) {

      $reservationItemData = $this->createReservationItemData($item, $data);

      // Set Products to Cart
      $products[] = [
        "id" => $reservationItemData['service']->product->id, // OJO - getProductAttribute - Version que ya estaba
        "quantity" => 1,
        "options" => ['reservationId' => $reservation->id, 'reservationItemData' => $reservationItemData['reservationItem']]
      ];

            //\Log::info("Ibooking: Services|CheckoutService|Create: ".json_encode($products));
        }

        // Create the Cart
        $cart = $cartService->create(['products' => $products]);

        return $cartService;
    }

    public function createReservation($data): reservation
    {
        // Get Customer Id if exist
        if (isset($data['customer_id'])) {
            $reservationData = ['customer_id' => $data['customer_id'], 'items' => []];
        }

        // If no exist is 0 (Pending)
        $reservationData['status'] = (int) setting('ibooking::reservationStatusDefault', null, 0);

        //\Log::info("Ibooking: Services|ReservationService|Create|reservationData ".json_encode($reservationData));
        $reservationRepository = app('Modules\Ibooking\Repositories\ReservationRepository');

        // Create Reservation and ReservationItem
        $reservation = $reservationRepository->create($reservationData);

        $reservationItemRepository = app('Modules\Ibooking\Repositories\ReservationItemRepository');
        // Add Reservation Item for ItemS
        foreach ($data['items'] as $item) {
            $reservationItemData = $this->createReservationItemData($item, $reservationData);
            $reservationItemData['reservationItem']['reservation_id'] = $reservation->id;
            $reservationItemRepository->create($reservationItemData['reservationItem']);
        }

    //Include items relation if entity
    $newReservationData = $reservationRepository->getItem($reservation->id, (object)[
      'include' => ['items.service.form']
    ]);

    // Send Email and Notification Iadmin
    event(new ReservationWasCreated($newReservationData));

        return $reservation;
    }

    /**
     * Get data from each item and create one array with the information
     *
     * @return array - [service,reservationItem]
     */
    public function createReservationItemData($item, $reservationData): array
    {
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

            //OJO CAMBIO A REVISAR
            $reservationItem['entity_type'] = "Modules\Ibooking\Entities\Resource";
            $reservationItem['entity_id'] = $resource->id;
        }

        if (isset($item['category_id'])) {
            $category = app("Modules\Ibooking\Repositories\CategoryRepository")->find($item['category_id']);
            $reservationItem['category_id'] = $category->id;
            $reservationItem['category_title'] = $category->title;
        }

        if (isset($item['start_date'])) {
            $reservationItem['start_date'] = $item['start_date'];
        }

        if (isset($item['end_date'])) {
            $reservationItem['end_date'] = $item['end_date'];
        }

        /*
        * OJO: Esto hay que revisarlo mejor xq la idea era que la Reservacion
        * agrupara todo, pero a nivel de frontend se dificulta
        */
        if (isset($reservationData['customer_id'])) {
            $reservationItem['customer_id'] = $reservationData['customer_id'];
        }

        if (isset($reservationData['status'])) {
            $reservationItem['status'] = $reservationData['status'];
        }

    // Save reservation item data
    // TODO: Revisar por que no estaba dejando todos los datos del item
    $response['reservationItem'] = array_merge($item, $reservationItem);

    return $response;

  }

  /**
   * Get emails and broadcast information
   */
  public function getEmailsAndBroadcast($reservation,$params=null)
  {


    //Emails from setting form-emails

      $emailTo = json_decode(setting("ibooking::formEmails", null, "[]"));
      if (empty($emailTo)) //validate if its a string separately by commas
        $emailTo = explode(',', setting('ibooking::formEmails'));

      //Emails from users selected in the setting usersToNotify
      $usersToNotify = json_decode(setting("ibooking::usersToNotify", null, "[]"));
      $users = User::whereIn("id", $usersToNotify)->get();
      $emailTo = array_merge($emailTo, $users->pluck('email')->toArray());
      $broadcastTo = $users->pluck('id')->toArray();

      //Get emails from the services form
      foreach ($reservation->items as $item) {
        $service = $item->service;//Get item service
        $serviceForm = $service ? $service->form->first() : null; //get form service

        //Get field from form to notify
        if ($serviceForm && isset($serviceForm->options) && isset($serviceForm->options->replyTo)) {
          $field = Field::find($serviceForm->options->replyTo);

          //Get field value and add it to emailTo
          if ($field) {
            $itemFields = $item->formatFillableToModel($item->fields);
            $itemFieldValue = $itemFields[$field->name] ?? $itemFields[snakeToCamel($field->name)] ??null;
            //Validate if has email format
            if ($itemFieldValue && filter_var($itemFieldValue, FILTER_VALIDATE_EMAIL))
              $emailTo[] = $itemFieldValue;
          }
        }
      }

      //Extra params from event
      if (!is_null($params)) {
        if (isset($params['broadcastTo'])) {
          $broadcastTo = array_merge($broadcastTo, $params['broadcastTo']);
          //\Log::info("Ibooking: Events|Handler|SendReservation|broadcastTo: ".json_encode($broadcastTo));
        }
      }

      if (!empty($reservation->customer_id)) {
        $emailReservation = $reservation->customer->email;
        array_push($emailTo, $emailReservation);
      }

      // Data Notification
      $to["email"] = $emailTo;
      $to["broadcast"] = $broadcastTo;

      return $to;

  }


}
