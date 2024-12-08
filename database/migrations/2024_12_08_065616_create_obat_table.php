<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('obat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_obat');
            $table->string('nama');
            $table->string('dosis');
            $table->integer('stok');
            $table->double('harga');
            $table->date('exp');
            $table->longText('keterangan');
            $table->string('foto')->nullable();
            $table->timestamps();

            $table->foreign('jenis_obat')->references('id')->on('jenis_obat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('obat');
    }
};
