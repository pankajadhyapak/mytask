<?php

namespace App;


class Team extends Model
{
    public function members()
    {
        return $this->belongsToMany(User::class, 'team_members')
                            ->withTimestamps();
    }

    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, "created_by");
    }

}
