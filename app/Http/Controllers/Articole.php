<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Articole extends Controller
{
    public function index()
    {
        return view('site.articole.index', [
            'controller' => __CLASS__
        ]);
    }
}
