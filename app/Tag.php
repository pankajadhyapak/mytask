<?php

namespace App;


class Tag extends Model
{
    public function taggable()
    {
        return $this->morphTo();
    }
}
