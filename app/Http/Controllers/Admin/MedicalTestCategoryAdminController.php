<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalTestCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MedicalTestCategoryAdminController extends Controller
{
    public function index()
    {
        return view('admin.medical_test_category.index', [
            'categories' => MedicalTestCategory::all()
        ]);
    }

    public function show(MedicalTestCategory $medicalTestCategory)
    {
        return view('admin.medical_test_category.show', [
            'model' => $medicalTestCategory
        ]);
    }

    public function create()
    {
        return view('admin.medical_test_category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'slug' => ['required', 'min:1', 'max:255',
                Rule::unique('medical_test_categories', 'slug')
            ]
        ]);

        $model = new MedicalTestCategory();
        $model->name = $request->get('name');
        $model->slug = $request->get('slug');
        $model->save();

        return redirect()->route('medical-test-categories.index');
    }

    public function edit(MedicalTestCategory $medicalTestCategory)
    {
        return view('admin.medical_test_category.edit', [
            'model' => $medicalTestCategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MedicalTestCategory $medicalTestCategory)
    {
        $request->validate([
            'name' => ['required', 'min:5', 'max:255'],
            'slug' => ['required', 'min:1', 'max:255',
                Rule::unique('medical_test_categories', 'slug')->ignore($medicalTestCategory->id)
            ]
        ]);

        $medicalTestCategory->update([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
        ]);

        return redirect()->route('medical-test-categories.index');
    }

    public function destroy(MedicalTestCategory $medicalTestCategory)
    {
        if ($medicalTestCategory->medicalTests()->count() > 0) {
            return redirect()->back()->with('error', 'MedicalTestCategory cannot be deleted - there are MedicalTests linked to it');
        } else {
            $medicalTestCategory->delete();
            return redirect()->back()->with('success', 'MedicalTestCategory has been deleted');
        }
    }
}
