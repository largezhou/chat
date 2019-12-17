<?php

namespace App\Admin\Controllers;

use App\Services\ConfigService;

class TestSomethingController extends Controller
{
    public function index($path = null)
    {
        dd(ConfigService::basic());
    }
}
