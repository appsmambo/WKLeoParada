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
// descargar el consolidado excel
Route::get('descargarConsolidado', array('uses' => 'HomeController@getConsolidado'));
// descargar el consolidado pdf
Route::get('descargarConsolidadoPDF', array('uses' => 'HomeController@getConsolidadoPDF'));
// limpia la base de datos
Route::get('reiniciar', array('uses' => 'HomeController@getReiniciarSistema'));

// ruta para el upload
Route::group(array('before' => 'csrf'), function()
{
  Route::post('proceso', array('uses' => 'HomeController@postProceso'));
});
