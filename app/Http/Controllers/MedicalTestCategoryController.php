<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicalTestCategoryRequest;
use App\Http\Requests\UpdateMedicalTestCategoryRequest;
use App\Models\MedicalTestCategory;

class MedicalTestCategoryController extends Controller
{

    public function index()
    {
        return view('medical_test_category.categories', [
            'categories' => MedicalTestCategory::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreMedicalTestCategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreMedicalTestCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MedicalTestCategory  $medicalTestCategory
     * @return \Illuminate\Http\Response
     */
    public function show(MedicalTestCategory $medicalTestCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\MedicalTestCategory  $medicalTestCategory
     * @return \Illuminate\Http\Response
     */
    public function edit(MedicalTestCategory $medicalTestCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateMedicalTestCategoryRequest  $request
     * @param  \App\Models\MedicalTestCategory  $medicalTestCategory
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateMedicalTestCategoryRequest $request, MedicalTestCategory $medicalTestCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MedicalTestCategory  $medicalTestCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicalTestCategory $medicalTestCategory)
    {
        //
    }
}
