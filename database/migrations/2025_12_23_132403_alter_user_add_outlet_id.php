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
        Schema::table('users', function (Blueprint $table) {
            // Menggunakan foreignId agar otomatis menjadi Unsigned Big Integer
            $table->foreignId('outlet_id')->nullable()->after('id');

            $table->boolean('is_active')->default(true)->after('avatar'); // Hapus nullable jika sudah ada default(true)
            $table->datetime('last_login')->after('is_active')->nullable();

            // Definisi foreign key
            $table->foreign('outlet_id')->references('id')->on('outlets')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Sangat disarankan hapus foreign key dulu sebelum hapus kolom
            $table->dropForeign(['outlet_id']);
            $table->dropColumn(['outlet_id', 'is_active', 'last_login']);
        });
    }
};
