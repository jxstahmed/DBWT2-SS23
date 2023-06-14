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
        Schema::create('ab_article', function (Blueprint $table) {
            $table->id();
            $table->string('ab_name',80)->nullable(false);
            $table->double('ab_price')->nullable(false);
            $table->string('ab_description',1000)->nullable(false);
            $table->timestamp('ab_createdate')->nullable(false);
            $table->unsignedBigInteger("ab_creator_id")->foreign('ab_creator_id')->references('id')->on('ab_user')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ab_article');
    }
};
