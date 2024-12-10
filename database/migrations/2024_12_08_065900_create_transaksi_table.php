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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_users');
            $table->unsignedBigInteger('id_instansi');
            $table->unsignedBigInteger('id_obat');
            // $table->unsignedBigInteger('id_jenis_obat'); ini ku hapus karena bisa panggil table obat dalamnya ada jenis obat + karena di table obat fk ke table jenis obatnya ga pakai nama id sedangkan waktu ini ada di transaksi panggilanya id_jenis_obat jadi bermasalah sementara ku hilangkan 
            $table->date('tanggal_order');
            $table->integer('jumlah_permintaan');
            $table->integer('jumlah_acc');
            $table->integer('jumlah_retur');
            $table->double('total_harga');
            $table->enum('status', ['Menunggu', 'Disetujui', 'Ditolak', 'Diretur']);
            $table->longText('alasan_penolakan')->nullable();
            $table->longText('alasan_retur')->nullable();
            $table->timestamps();

            $table->foreign('id_users')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_instansi')->references('id')->on('instansi')->onDelete('cascade');
            $table->foreign('id_obat')->references('id')->on('obat')->onDelete('cascade');
            // $table->foreign('id_jenis_obat')->references('id')->on('jenis_obat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
