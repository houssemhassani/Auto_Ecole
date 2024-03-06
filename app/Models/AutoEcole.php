<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AutoEcole extends Model
{
    protected $fillable = [
        'name', 'address', 'num_tel', 'email',
    ];

    public function monitors()
    {
        return $this->hasMany(Monitor::class);
    }
}
