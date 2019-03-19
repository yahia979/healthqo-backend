<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Comment extends Model
{
    protected $fillable = [
         'content','user_id','post_id'
    ];
}
