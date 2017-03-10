<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InterviewQuestion extends Model
{
		protected $table = 'interviewquestions';

        protected $fillable = [
        'question', 'answer', 'user_id',
    ];

    public function user(){
    	return $this->belongsTo('App\User');
    }
}
