<?php

use Illuminate\Routing\Router;

Route::prefix('/ibooking/v1')->group(function (Router $router) {
  $router->apiCrud([
    'module' => 'ibooking',
    'prefix' => 'categories',
    'controller' => 'CategoryApiController',
    'middleware' => ['index' => []], // Just Testing
  ]);
  $router->apiCrud([
    'module' => 'ibooking',
    'prefix' => 'services',
    'controller' => 'ServiceApiController',
    'middleware' => ['index' => []], // Just Testing
  ]);
  $router->apiCrud([
    'module' => 'ibooking',
    'prefix' => 'resources',
    'controller' => 'ResourceApiController',
    'middleware' => ['index' => [], 'show' => []], // Just Testing
  ]);
  $router->apiCrud([
    'module' => 'ibooking',
    'prefix' => 'reservations',
    'controller' => 'ReservationApiController',
    'middleware' => ['create' => ['optional-auth']], // Just Testing
  ]);
  $router->apiCrud([
    'module' => 'ibooking',
    'prefix' => 'reservation-items',
    'controller' => 'ReservationItemApiController',
    'permission' => 'ibooking.reservationitems'
    //'middleware' => ['create' => [],'index' => [],'delete' => []] // Just Testing
  ]);
  $router->get('availabilities', [
    'as' => 'api.ibooking.availability',
    'uses' => 'AvailabilityApiController@availability',
  ]);
  $router->apiCrud([
    'module' => 'iwebhooks',
    'prefix' => 'statuses',
    'staticEntity' => 'Modules\Ibooking\Entities\Status',
    //'middleware' => ['create' => [], 'index' => [], 'show' => [], 'update' => [], 'delete' => [], 'restore' => []]
  ]);
  $router->apiCrud([
    'module' => 'iwebhooks',
    'prefix' => 'resource-value-types',
    'staticEntity' => 'Modules\Ibooking\Entities\ResourceValueType',
    //'middleware' => ['create' => [], 'index' => [], 'show' => [], 'update' => [], 'delete' => [], 'restore' => []]
  ]);

  // append
});
