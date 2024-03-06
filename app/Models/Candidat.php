<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidat extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'cin',
        'adresse',
        'date_of_birth',
        'num_tel',
    ];

    protected $casts = [
        'date_of_birth' => 'datetime',
    ];

    public function cour()
    {
        return $this->belongsTo(Cour::class);
    }


}
