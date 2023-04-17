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
        Schema::create('ab_articlecategory', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ab_name',100)->unique()->nullable(false);
            $table->string('ab_description',1000)->nullable(true);
            $table->unsignedBigInteger('ab_parent')->nullable(true);
            $table->foreign('ab_parent')->on('ab_articlecategory')->references('id'); // Wir referenzieren auf die eigene Tabelle, falls es Eltern gibt.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ab_articlecategory');
    }
};
