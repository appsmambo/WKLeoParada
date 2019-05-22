<?php

// archivo de rutas HTTP

// página de inicio, se muestra el formulario de upload e historial
Route::get('/', array('uses' => 'HomeController@getHome'));
// ver el historial de uploads
Route::get('historial', array('uses' => 'HomeController@getHistorial'));
// ver el detalle del upload
Route::get('getHistorial/{idProceso}', array('uses' => 'HomeController@getHistorialById'));
// ver el consolidado
Route::get('consolidado', array('uses' => 'HomeController@getOnline'));
// descargar el consolidado
Route::get('descargarConsolidado', array('uses' => 'HomeController@getConsolidado'));

// ruta para el upload
Route::group(array('before' => 'csrf'), function()
{
  Route::post('proceso', array('uses' => 'HomeController@postProceso'));
});