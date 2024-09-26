<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;

class Project extends Model
{
    use HasUuid, SoftDeletes;

    protected $guarded = [];
    protected $appends = ["users"];

    // protected $fillable = ['code', 'user_id', 'tag','logo', 'uuid'];

    public function getUsersAttribute()
    {
        // return $this->hasOne(User::class,'user_id');
        $user = User::find($this->user_id);
        return $user == null ? ["name" => ""] : $user->toArray();
    }

    public function videos()
    {
        return $this->hasMany(Video::class, 'pid', 'id');
    }
}