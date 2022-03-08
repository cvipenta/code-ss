<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AnalizeMedicale extends Controller
{
    public function index()
    {
        return $this->categories();
    }

    public function categories()
    {
        $categories = DB::table('analize_medicale_all')->distinct()->pluck('am_category')->toArray();
        $categories = array_map(static function ($category) {
            return [
                'title' => ucfirst($category) ?: 'Fara categorie',
                'slug' => Str::slug($category) ?: 'na'
            ];
        }, $categories);

        return view('analize_medicale.categories', [
            'categories' => $categories
        ]);
    }

    public function category($category)
    {
        if ($category === 'na') {
            $category = null;
        }

        $records = DB::table('analize_medicale_all')
                     ->where('am_category', $category)
                     ->orWhere('am_category', str_replace('-', ' ', $category))
                     ->get();

        foreach ($records as $record) {
            DB::table('analize_medicale_all')
              ->where('am_id', $record->am_id)
              ->update([
                  'am_slug' => Str::slug($record->am_title)
              ]);
        }

        return view('analize_medicale.category', [
            'records' => $records
        ]);
    }

    public function show(\App\Models\AnalizeMedicale $model)
    {
        return view('analize_medicale.show', [
            'record' => $model
        ]);
    }
}
