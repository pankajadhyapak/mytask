<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{
    protected $guarded = [];

    protected $with = ['owner'];

    protected $dates = ['date'];

    public function loggable()
    {
        return $this->morphTo();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
