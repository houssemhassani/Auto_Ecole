<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $fillable = ['candidat_id', 'question_id', 'answer_id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class);
    }

    public function candidat()
    {
        return $this->belongsTo(Candidat::class);
    }
}
