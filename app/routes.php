<?php

Route::get('/', array('uses' => 'HomeController@getHome'));
Route::get('historial', array('uses' => 'HomeController@getHistorial'));
Route::get('getHistorial/{idProceso}', array('uses' => 'HomeController@getHistorialById'));
Route::get('consolidado', array('uses' => 'HomeController@getOnline'));
Route::get('descargarConsolidado', array('uses' => 'HomeController@getConsolidado'));

//Route::post('proceso', array('before' => 'csrf', function()
Route::group(array('before' => 'csrf'), function()
{
  Route::post('proceso', array('uses' => 'HomeController@postProceso'));
});