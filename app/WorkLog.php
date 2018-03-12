<?php

namespace App;


class WorkLog extends Model
{
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
