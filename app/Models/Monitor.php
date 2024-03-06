<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Monitor extends Model
{
    protected $fillable = [
        'num_professional', 'num_cin', 'auto_ecole_id',
    ];

    public function autoEcole()
    {
        return $this->belongsTo(AutoEcole::class);
    }
}
