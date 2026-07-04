<?php

namespace Elmasry\StarterKit\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'sort_order',
    ];

    public $translatable = [
        'name',
        'slug',
    ];

    public function taggable()
    {
        return $this->morphToMany();
    }
}
