<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreMedicalTestCategoryRequest;
use App\Http\Requests\Admin\UpdateMedicalTestCategoryRequest;
use App\Models\MedicalTestCategory;
use Illuminate\Http\Response;

class MedicalTestCategoryAdminController extends Controller
{

    public function index()
    {
        return view('medical_test_category.index', [
            'categories' => MedicalTestCategory::all()
        ]);
    }

    public function show(MedicalTestCategory $medicalTestCategory)
    {
        $records = $medicalTestCategory->medicalTests()->paginate(7, ['title', 'slug']);

        return view('medical_test_category.show', [
            'records' => $records
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreMedicalTestCategoryRequest $request
     * @return Response
     */
    public function store(StoreMedicalTestCategoryRequest $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param MedicalTestCategory $medicalTestCategory
     * @return Response
     */
    public function edit(MedicalTestCategory $medicalTestCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateMedicalTestCategoryRequest $request
     * @param MedicalTestCategory $medicalTestCategory
     * @return Response
     */
    public function update(UpdateMedicalTestCategoryRequest $request, MedicalTestCategory $medicalTestCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param MedicalTestCategory $medicalTestCategory
     * @return Response
     */
    public function destroy(MedicalTestCategory $medicalTestCategory)
    {
        //
    }
}
