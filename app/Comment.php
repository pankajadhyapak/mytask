<?php

namespace App;


class Comment extends Model
{
    protected $with = ['owner'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function owner()
    {
        return $this->belongsTo(User::class, "user_id");
    }
}
