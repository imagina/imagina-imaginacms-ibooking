<?php

use Illuminate\Routing\Router;

$router->group(['prefix' =>'/ibooking/v1'], function (Router $router) {
    $router->apiCrud([
      'module' => 'ibooking',
      'prefix' => 'categories',
      'controller' => 'CategoryApiController',
      'middleware' =>  ['create' => [],'index' => [],'delete' => []] // Just Testing
    ]);
    $router->apiCrud([
      'module' => 'ibooking',
      'prefix' => 'services',
      'controller' => 'ServiceApiController',
      'middleware' => ['create' => [],'index' => [],'delete' => []] // Just Testing
    ]);
    $router->apiCrud([
      'module' => 'ibooking',
      'prefix' => 'resources',
      'controller' => 'ResourceApiController',
      'middleware' => ['create' => [],'index' => [],'delete' => []] // Just Testing
    ]);
    $router->apiCrud([
      'module' => 'ibooking',
      'prefix' => 'reservations',
      'controller' => 'ReservationApiController',
      'middleware' => ['create' => [],'index' => [],'delete' => []] // Just Testing
    ]);
// append




});
