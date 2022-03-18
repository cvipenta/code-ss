<?php

namespace App\Http\Controllers;

class DictionarMedical extends Controller
{
    public function index()
    {
        return view('site.dictionar_medical.index', [
            'controller' => __CLASS__
        ]);
    }
}
