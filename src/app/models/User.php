<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model; 

class User extends Model {

    protected $fillable = [
        'name', 'email', 'password'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected function created_at()
    {
        return Attribute::make(
            get: fn ($value) => now()->createFromFormat('Y-m-d H:i:s', $value, 'utc')->timezone(config('app.timezone')),
        );
    }

    protected function updated_at()
    {
        return Attribute::make(
            get: fn ($value) => now()->createFromFormat('Y-m-d H:i:s', $value, 'utc')->timezone(config('app.timezone')),
        );
    }
}