<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbArticleHasArticleCategory extends Model
{
    protected $primaryKey = 'id';

    protected $table = 'ab_article_has_articlecategory';

    public $timestamps = false;

}
