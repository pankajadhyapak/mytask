<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
    protected $guarded = [];

    public function statusable()
    {
        return $this->morphTo();
    }
}
