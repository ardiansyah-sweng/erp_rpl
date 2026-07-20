<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Membuat tabel activity_logs untuk mencatat log aktivitas user.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable(); // simpan nama user untuk histori
            $table->string('action', 50); // create, update, delete
            $table->string('module', 100); // branch, warehouse, supplier, dll
            $table->text('description'); // deskripsi aktivitas
            $table->string('target_id', 100)->nullable(); // ID record yang terpengaruh
            $table->string('ip_address', 45)->nullable(); // IP address user
            $table->timestamps();

            // Index untuk performa query
            $table->index('user_id');
            $table->index('action');
            $table->index('module');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
