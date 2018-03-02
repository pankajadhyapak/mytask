<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded = [];

    protected $with = ['assigned', 'owner', 'status'];

    protected $appends = ['is_completed'];

    public function assigned()
    {
        return $this->belongsTo(User::class, "assigned_to");
    }

    public function owner()
    {
        return $this->belongsTo(User::class, "created_by");
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function worklogs()
    {
        return $this->morphMany(WorkLog::class, 'loggable')->latest();
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    //TODO think of better logic
    public function getIsCompletedAttribute()
    {
        return $this->status
            ->where(["id" => $this->attributes['status_id'], "defines_complete" => 1])
            ->exists();
    }

    public function markAsComplete($projectId)
    {
        $status = Status::getComplete($projectId);
        $this->status_id = $status->id;
        $this->save();
        return $status;
    }

}
