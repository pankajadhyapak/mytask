<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $with = ['assigned', 'owner'];

    public function assigned()
    {
        return $this->belongsTo(User::class, "assigned_to");
    }

    public function owner()
    {
        return $this->belongsTo(User::class, "created_by");
    }
}
