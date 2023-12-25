<?php
namespace App\Models;

use App\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model; 

class Mail extends Model {

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeThisUser($query)
    {
        return $query->where('user_id', Auth::user()->id);
    }

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