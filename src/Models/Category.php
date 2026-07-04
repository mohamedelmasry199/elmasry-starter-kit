<?php

namespace Elmasry\StarterKit\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'status',
        'sort_order',
    ];

    public $translatable = [
        'name',
        'slug',
        'description',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }
}
