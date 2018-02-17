<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $guarded = [];

    protected $appends = ['total_estimated'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function getTotalEstimatedAttribute()
    {
        return $this->tasks()->sum('estimated_time');
    }

    public function tasks()
    {
        return $this->hasMany(Task::class)->latest();
    }
}
