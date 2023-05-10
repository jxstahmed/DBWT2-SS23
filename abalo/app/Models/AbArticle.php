<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbArticle extends Model
{
    protected $table = 'ab_article';

    protected $fillable = [
      'id',
      'ab_name',
      'ab_price',
      'ab_description',
      'ab_creator_id',
      'ab_createdate'
    ];

    public $timestamps = false;

    protected $appends = ["image"];

    public function getImageAttribute() {
        $bild = glob("articlesimages/{$this->id}.*");
        if(empty($bild[0]) === false) {
            return $bild[0];
        } else {
            return null;
        }
    }
}
