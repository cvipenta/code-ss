<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateHits;
use App\Models\MedicalTest;

class MedicalTestController extends Controller
{
    public function show(MedicalTest $medicalTest)
    {
        UpdateHits::dispatch($medicalTest)
            ->onConnection('redis')
            ->onQueue('medical_test.hits');

        return view('site.medical_test.show', [
            'record' => $medicalTest
        ]);
    }
}
