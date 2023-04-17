<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbArticleCategory extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'ab_articlecategory';

    protected $fillable = [

        'id',
        'ab_name',
        'ab_description',
        'ab_parent'
    ];

    public $timestamps = false;
}
