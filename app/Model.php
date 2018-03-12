<?php
/**
 * Created by PhpStorm.
 * User: pankaj
 * Date: 13/03/18
 * Time: 12:52 AM
 */

namespace App;


use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use SoftDeletes;

    protected $guarded = [];
}