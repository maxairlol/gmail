<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $fillable = ['name'];
    public $timestamps = false;

    public function gmails()
    {
        return $this->belongsToMany(Gmail::class);
    }

}
