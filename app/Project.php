<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $guarded = [];

    public function team()
    {
        return $this->belongsTo(Team::class);
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
