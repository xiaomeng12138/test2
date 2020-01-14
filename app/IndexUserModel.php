<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IndexUserModel extends Model
{
    public $primaryKey='user_id';
    public $table="index_user";
    protected $guarded = [];
    public $timestamps = false;
}
