<?php

namespace App\Admin\Controllers;

class TestSomethingController extends Controller
{
    public function index($path = null)
    {
        dd($path);
    }
}
