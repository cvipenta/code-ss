<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DictionarMedicamente extends Controller
{
    public function index()
    {
        return view('site.dictionar_medicamente.index', [
            'controller' => __CLASS__
        ]);
    }
}
