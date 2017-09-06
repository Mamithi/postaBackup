<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    public $fillable = ['name', 'box','code', 'town', 'person_id' 'status'];
}
