<?php

Route::get('/', function()
{
  return View::make('home');
});

//Route::post('proceso', array('before' => 'csrf', function()
Route::group(array('before' => 'csrf'), function()
{
  Route::post('proceso', array('uses' => 'HomeController@postProceso'));
});