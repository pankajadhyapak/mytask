<?php

namespace App;


class Invite extends Model
{
    protected $with = ['by', 'team'];

    public function by()
    {
        return $this->belongsTo(User::class, "invited_by");
    }
    public function team()
    {
        return $this->belongsTo(Team::class, "team_id");
    }
}
