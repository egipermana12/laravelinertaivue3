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
        Schema::create('sidebars', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            $table->string('url', 100)->nullable()->default('#');
            $table->string('icon', 100)->default('SquareTerminal');
            $table->boolean('isActive')->default(false);
            $table->integer('parentId')->default(0);
            $table->integer('sortOrder')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidebars');
    }
};
