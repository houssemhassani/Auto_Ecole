<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicule extends Model
{
    use HasFactory;

    protected $fillable = ['brand', 'model', 'year', 'registration_number', 'monitor_id', 'image'];

    /**
     * Get the monitor that owns the vehicle.
     */
    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }
}
