<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('ab_shoppingcart_item', function (Blueprint $table) {
                    $table->id();
                    $table->bigInteger('ab_shoppingcart_id')->nullable(false);
                    $table->bigInteger('ab_article_id')->nullable(false);
                    $table->timestamp('ab_createdate')->nullable(false);
                    $table->foreign('ab_article_id')->references('id')->on('ab_article');
                    $table->foreign('ab_shoppingcart_id')->references('id')->on('ab_shoppingcart')->onDelete('cascade');
                });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ab_shoppingcart_item');
    }
};
