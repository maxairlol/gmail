<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gmail extends Model
{
    protected $fillable = ['id', 'user_id', 'from', 'subject', 'date'];
}
