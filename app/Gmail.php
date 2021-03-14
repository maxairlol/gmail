<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gmail extends Model
{
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';
    protected $fillable = ['id', 'user_id', 'from', 'subject', 'content', 'date'];

    public function labels()
    {
        return $this->belongsToMany(Label::class);
    }
}
