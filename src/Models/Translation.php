<?php

namespace Elmasry\StarterKit\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $fillable = [
        'group',
        'key',
        'value',
        'locale',
    ];
}
