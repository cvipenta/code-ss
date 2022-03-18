<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Homepage extends Controller
{
    public function index()
    {
        return view('site.homepage.index', [
            'controller' => __CLASS__
        ]);
    }
}
