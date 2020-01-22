<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    protected $table = "posti";

    public function classroom(){
        return $this->belongsTo('App\Classroom');
    }

    public function user(){
        return $this->belongsTo('App\Occupation');
    }
}