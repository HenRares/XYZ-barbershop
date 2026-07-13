<?php

namespace App\Http\Controllers;

use App\Models\Service;

class HomeController extends Controller
{
    public function index() { return view('home', ['services' => Service::active()->orderBy('id')->get()]); }
}
