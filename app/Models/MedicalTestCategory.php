<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalTestCategory extends Model
{
    use HasFactory;

    /**
     * Get the comments for the blog post.
     */
    public function medicalTests(): HasMany
    {
        return $this->hasMany(MedicalTest::class, 'category_id', 'id');
    }
}
