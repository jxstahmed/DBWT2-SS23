<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $table = 'ab_shoppingcart';

    protected $fillable = [
        'id',
        'ab_creator_id',
        'ab_createdate',
    ];
    public $timestamps = false;

    public function item() {
        return $this->hasOne(ShoppingCartItem::class, 'ab_shoppingcart_id', 'id');
    }

    public function items() {
        return $this->hasMany(ShoppingCartItem::class, 'ab_shoppingcart_id', 'id');
    }
}
