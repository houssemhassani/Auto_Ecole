<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cour extends Model
{
    protected $fillable = [
        'monitor_id',
        'titre',
        'description',
        'date_debut',
        'date_fin',
        'nombre_heures',
        'type',
        'termine'
    ];

    public function monitor()
    {
        return $this->belongsTo(Monitor::class);
    }

    public function candidats()
    {
        return $this->belongsToMany(Candidat::class)->withPivot('type', 'termine');
    }
}
