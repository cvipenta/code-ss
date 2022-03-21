<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $name
 * @property string $slug
 *
 * @property MedicalTest[] $medicalTests
 */
class MedicalTestCategory extends Model
{
    use HasFactory;

    public $guarded = [];

    /**
     * Get the comments for the blog post.
     */
    public function medicalTests(): HasMany
    {
        return $this->hasMany(MedicalTest::class, 'category_id', 'id')->orderBy('title');
    }
}
