<?php

namespace App\Http\Controllers;

use App\Jobs\UpdateHits;
use App\Models\MedicalTest;

class MedicalTestController extends Controller
{
    public function show(MedicalTest $medicalTest)
    {
        UpdateHits::dispatch($medicalTest, 'theSecondArgument')
            ->onConnection('redis')
            ->onQueue('medical_test.hits');

        return view('medical_test.show', [
            'record' => $medicalTest
        ]);
    }
}
