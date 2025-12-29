<?php

namespace App\Models;

use Illuminate\Database\Eloquent\MassPrunable;
use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    use MassPrunable;
    protected $fillable = [
        'email',
        'code',
        'expires_at',
    ];

    public function prunable()
    {
        return static::where('expires_at', '<', now());
    }
}
