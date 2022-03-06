<?php

namespace App\Http\Controllers;

class ArticoleRss extends Controller
{
    public function index()
    {
        return view('articole_rss.index', [
            'controller' => __CLASS__
        ]);
    }
}
