<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/home-data', 'IndexController@homeData');

Route::get('/proxy/list', 'ProxyController@list');
Route::post('/proxy/save', 'ProxyController@save');
Route::post('/proxy/remove', 'ProxyController@remove');
Route::post('/proxy/generate-conf', 'ProxyController@generateConf');

Route::get('/certificate/certificate-config/index-data', 'CertificateConfigController@indexData');
Route::get('/certificate/certificate-config/list', 'CertificateConfigController@list');
Route::post('/certificate/certificate-config/save', 'CertificateConfigController@save');
Route::post('/certificate/certificate-config/remove', 'CertificateConfigController@remove');

Route::get('/certificate/certificate/index-data', 'CertificateController@indexData');
Route::get('/certificate/certificate/list', 'CertificateController@list');
Route::get('/certificate/certificate/select-list', 'CertificateController@selectList');
Route::post('/certificate/certificate/save', 'CertificateController@save');
Route::post('/certificate/certificate/remove', 'CertificateController@remove');
