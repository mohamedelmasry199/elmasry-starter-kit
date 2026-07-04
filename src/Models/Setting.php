<?php

namespace Elmasry\StarterKit\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    public function scopeByGroup($query, $group)
    {
        return $query->where('group', $group);
    }
}
