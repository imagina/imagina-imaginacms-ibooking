<?php
use Illuminate\Routing\Router;

$locale = LaravelLocalization::setLocale() ?: App::getLocale();

/** @var Router $router */
Route::group(['prefix' => LaravelLocalization::setLocale()], function (Router $router) use ($locale) {

    $router->post(trans('ibooking::routes.service.index').'/'.trans('ibooking::routes.service.buy'), [
        'as' => 'services.buyService',
        'uses' => 'PublicController@buyService',
    ]);
   
});
