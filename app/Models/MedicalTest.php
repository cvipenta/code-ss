<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class MedicalTest extends Model
{
    use HasFactory;

    public function category(): Relation
    {
        return $this->belongsTo(MedicalTestCategory::class/*, 'category_id'*/);
    }
}
