<?php

namespace App\Http\Controllers;

use App\Models\MedicalTest;

class MedicalTestController extends Controller
{
    public function show(MedicalTest $medicalTest)
    {
        return view('medical_test_show', [
            'record' => $medicalTest
        ]);
    }
}
