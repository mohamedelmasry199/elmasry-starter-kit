<?php

namespace Elmasry\StarterKit\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'locale',
        'timezone',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function pages()
    {
        return $this->hasMany(Page::class, 'author_id');
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'causer_id');
    }
}
