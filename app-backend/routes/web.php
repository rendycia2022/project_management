<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return '"Project Management" '.$router->app->version();
});

$router->group(['prefix' => 'api/'], function () use ($router) {

    $router->group(['prefix' => 'v2'], function () use ($router) {

        $router->group(['prefix' => 'auth'], function () use ($router) {
        
            $router->post('/token','v2\auth\TokenController@store');
            $router->get('/token/{token}','v2\auth\TokenController@get');
            $router->delete('/token/{token}','v2\auth\TokenController@destroy');
        });

    });

    $router->group(['prefix' => 'project'], function () use ($router) {

        // af
        $router->get('/af/po','project\AFController@showByPO');

        // chart 
        $router->get('/chart/revenue','project\ProjectChart_Controller@revenue');
        $router->get('/chart/revenue/{year}','project\ProjectChart_Controller@revenueYear');

        // list
        $router->get('/list','project\ProjectList_Controller@show');
        $router->post('/list/remarks','project\ProjectList_Controller@updateRemarks'); // remarks

        // history
        $router->get('/update/{day}/{all}','project\UpdateController@show');
        $router->get('/approvalForm','project\AFController@show');
        
        // sites
        $router->get('/sites/menus','project\SitesController@getMenus');
    });
});

?>
