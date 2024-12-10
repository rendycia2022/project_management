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
            $router->get('/token','v2\auth\TokenController@get');
            $router->delete('/token/{token}','v2\auth\TokenController@destroy');

            // login to project report apps
            $router->get('/token/project_apps/{token}','v2\auth\TokenController@getLoginProject');
        });

    });

    $router->group(['prefix' => 'project'], function () use ($router) {

        // new start
        $router->group(['prefix' => 'new'], function () use ($router) {
            $router->get('/list','project\ListController@show');
            $router->get('/list/bypo','project\ListController@showByPo');
            $router->post('/list/remarks','project\ListController@updateRemarks'); // remarks

            $router->get('/chart','project\ChartController@show');

            $router->get('/bast/bypo','project\BASTController@showByPo');
            $router->post('/bast/{user_id}','project\BASTController@store');
            $router->get('/bast/backend/target','project\BASTController@backend');
            $router->get('/bast/download/template','project\BASTController@download');
            $router->delete('/bast','project\BASTController@destroy');
            
        });
        // new end

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

    $router->group(['prefix' => 'public'], function () use ($router) {

        // file
        $router->get('/file/po','public\FilesController@get_po');
    });

});

?>
