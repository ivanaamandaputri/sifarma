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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'operator']);
            $table->string('profile')->nullable();
            $table->string('nama');
            $table->enum('jabatan', ['Admin', 'Kepala Apotik', 'Apoteker', 'Staff']);
            $table->unsignedBigInteger('id_instansi');
            $table->timestamps();

            // Definisi foreign key
            $table->foreign('id_instansi')->references('id')->on('instansi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
