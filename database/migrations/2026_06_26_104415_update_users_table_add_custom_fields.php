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
        $table->integer('masjid_id')->nullable()->after('id');
        $table->string('nama')->after('masjid_id');
        $table->string('ic_number', 20)->after('nama');
        $table->integer('role')->nullable()->after('password');
        $table->enum('status', ['active', 'inactive'])->default('active')->after('role');
        $table->string('tel_number', 50)->nullable()->after('remember_token');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['masjid_id', 'nama', 'ic_number', 'role', 'status', 'tel_number']);
    });
}
};
