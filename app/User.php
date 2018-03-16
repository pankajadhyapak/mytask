<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $appends = ['display_name'];

    public function getDisplayNameAttribute()
    {
        return $this->attributes['name'];
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function teams()
    {
        return $this->belongsToMany(Team::class, "team_members");
    }

    public function projects()
    {
        return Project::whereIn("team_id", $this->teams->pluck("id"))->get();
    }

    public function tasks()
    {
        return $this->hasMany(Task::class, "assigned_to");
    }
}
