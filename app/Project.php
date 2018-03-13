<?php

namespace App;

class Project extends Model
{

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function tasks()
    {
        return $this->hasManyThrough(Task::class, Module::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function statuses()
    {
        return $this->morphMany(Status::class, 'statusable')->latest();
    }
}
