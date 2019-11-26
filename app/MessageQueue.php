<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageQueue extends Model
{
    public $timestamps = false;
    protected $table = "messages_queue";
}
