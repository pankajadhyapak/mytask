<?php

namespace App;


class Status extends Model
{
    public static $defaultStatus = [
        [
            "name" => "To Do",
            "default" => true,
            "defines_complete" => false
        ],
        [
            "name" => "In Progress",
            "default" => false,
            "defines_complete" => false
        ],
        [
            "name" => "Done",
            "default" => false,
            "defines_complete" => true
        ],
    ];

    public function statusable()
    {
        return $this->morphTo();
    }

    public static function getComplete($projectId)
    {
        return static::where("statusable_type", "App\Project")
            ->where("statusable_id", $projectId)
            ->where("defines_complete", 1)->firstOrFail();
    }

    public static function getDefault($projectId)
    {
        return static::where("statusable_type", "App\Project")
            ->where("statusable_id", $projectId)
            ->where("default", 1)->firstOrFail();
    }
}
