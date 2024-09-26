<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Video extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    protected $appends = ['settings'];

    protected $casts = [
        'settings2' => 'json'
    ];
}