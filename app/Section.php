<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
class Section extends Model
{
    use Notifiable;
    protected $fillable = [
       'title', 'image'
    ];
}
