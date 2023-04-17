<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbUser extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'ab_user';

    protected $fillable = [

        'id',
        'ab_name',
        'ab_password',
        'ab_mail'
    ];

    public $timestamps = false;
}
