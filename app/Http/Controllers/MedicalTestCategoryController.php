<?php

namespace App\Http\Controllers;

use App\Models\MedicalTestCategory;

class MedicalTestCategoryController extends Controller
{

    public function index()
    {
        return view('site.medical_test_category.index', [
            'categories' => MedicalTestCategory::all()
        ]);
    }

    public function show(MedicalTestCategory $medicalTestCategory)
    {
        $records = $medicalTestCategory->medicalTests()->paginate(7, ['title', 'slug']);

        return view('site.medical_test_category.show', [
            'records' => $records
        ]);
    }
}
