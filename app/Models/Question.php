<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['text', 'coefficient','image','duration'];

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }


    public function userAnswers()
    {
        return $this->hasMany(UserAnswer::class);
    }
}
