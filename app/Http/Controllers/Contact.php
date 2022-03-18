<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Contact extends Controller
{
    public function index()
    {
        return view('site.contact.index', [
            'controller' => __CLASS__
        ]);
    }
}
