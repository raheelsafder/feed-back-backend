<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeedBack extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function comments()
    {
        return $this->hasMany(FeedBackComment::class);
    }

    public function user()
    {
        return $this->hasOne(User::class , 'id' , 'user_id');
    }
}
