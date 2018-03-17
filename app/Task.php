<?php

namespace App;


class Task extends Model
{

    protected $with = ['assigned', 'owner', 'status', 'tags'];

    protected $casts = ['is_completed' => 'bool'];

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

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable')->latest();
    }

    public function worklogs()
    {
        return $this->morphMany(WorkLog::class, 'loggable')->latest();
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function markAsComplete($projectId)
    {
        $status = Status::getComplete($projectId);
        $this->status_id = $status->id;
        $this->is_completed = true;
        $this->save();
        return $status;
    }

}
