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
        Schema::create('outlets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('franchise_id');
            $table->string('outlet_name', 100);
            $table->string('unique_code', 50)->unique();
            $table->string('owner_name', 100);
            $table->string('address', 255)->nullable();
            $table->string('kelurahan', 5)->nullable();
            $table->string('kecamatan', 5)->nullable();
            $table->string('city', 5)->nullable();
            $table->string('province', 5)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('latitude', 100)->nullable();
            $table->string('longitude', 100)->nullable();
            $table->timestamps();
            $table->foreign('franchise_id')->references('id')->on('franchises')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};
