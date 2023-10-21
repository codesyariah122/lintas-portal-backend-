<?php
/**
* @author : Puji Ermanto <pujiermanto@gmail.com>
* @return {Router::class}
**/
use App\Middleware\JsonResponseMiddleware;
use App\Middleware\AuthenticationMiddleware;
use App\Config\Router;


$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '');
$trimmedUri = rtrim($uri, '/add-role');

if($trimmedUri !== "/api/auth") {    

    Router::get('/', 'HomeController@index');

    Router::jsonMiddleware(JsonResponseMiddleware::class, function () {
        Router::group('/api/public', function (){
            Router::get('/get-ip', 'Api\Public\GeoLocatorController@getIp');
            Router::get('/geo-locator', 'Api\Public\GeoLocatorController@getLocator');
            Router::get('/province-lists', 'Api\Public\GeoLocatorController@provinceLists');
            Router::get('/city-lists', 'Api\Public\GeoLocatorController@cityLists');
            Router::get('/subdistrict', 'Api\Public\GeoLocatorController@subDistrict');
            Router::get('/ward-lists', 'Api\Public\GeoLocatorController@wardLists');
            Router::get('/search-location', 'Api\Public\GeoLocatorController@searchLocation');

            // Roles user lists
            Router::get('/roles', 'Api\Auth\RoleController@all');
        });

        Router::group('/api/user', function(){
            Router::post('/new-register', 'Api\Auth\RegisterController@create');
        });

    });
} else {
    Router::authMiddleware(AuthenticationMiddleware::class, function() {
        Router::group('/api/auth', function() {
            Router:;post('/add-role', 'Api\Auth\RoleController@create');
        });
    });
}


Router::run();