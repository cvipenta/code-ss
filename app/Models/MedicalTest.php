<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property int $category_id
 * @property int $hits
 *
 * @property MedicalTestCategory $category
 */
class MedicalTest extends Model
{
    use HasFactory;

    public function category(): Relation
    {
        return $this->belongsTo(MedicalTestCategory::class/*, 'category_id'*/)->withDefault();
    }
}
