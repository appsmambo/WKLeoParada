<?php

class HomeController extends BaseController {

	public function getHome()
  {
    //$proceso = Proceso::take(10)->orderBy('created_at', 'desc')->get();
    return View::make('home');
  }

}
