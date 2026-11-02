<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel utama: biodata / portofolio
        Schema::create('Portofolio_1104', function (Blueprint $table) {
            $table->increments('id_portofolio');
            $table->string('nama_lengkap', 100);
            $table->string('profesi', 100);
            $table->text('profil')->nullable();
            $table->string('telepon', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('alamat', 255)->nullable();
            $table->string('foto', 255)->nullable();
            $table->boolean('tampilkan_foto')->default(true); // include / exclude gambar
            $table->timestamps();
        });

        // Tabel bonus 1: riwayat pendidikan
        Schema::create('Pendidikan_1104', function (Blueprint $table) {
            $table->increments('id_pendidikan');
            $table->unsignedInteger('id_portofolio');
            $table->string('tahun_mulai', 4)->nullable();
            $table->string('tahun_selesai', 4)->nullable();
            $table->string('institusi', 150);
            $table->string('jurusan', 150)->nullable();
            $table->timestamps();

            $table->foreign('id_portofolio')->references('id_portofolio')
                ->on('Portofolio_1104')->cascadeOnDelete();
        });

        // Tabel bonus 2: pengalaman kerja
        Schema::create('Pengalaman_1104', function (Blueprint $table) {
            $table->increments('id_pengalaman');
            $table->unsignedInteger('id_portofolio');
            $table->string('tahun_mulai', 4)->nullable();
            $table->string('tahun_selesai', 4)->nullable();
            $table->string('jabatan', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->foreign('id_portofolio')->references('id_portofolio')
                ->on('Portofolio_1104')->cascadeOnDelete();
        });

        // Tabel bonus 3: keahlian
        Schema::create('Keahlian_1104', function (Blueprint $table) {
            $table->increments('id_keahlian');
            $table->unsignedInteger('id_portofolio');
            $table->string('nama_keahlian', 100);
            $table->timestamps();

            $table->foreign('id_portofolio')->references('id_portofolio')
                ->on('Portofolio_1104')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('Keahlian_1104');
        Schema::dropIfExists('Pengalaman_1104');
        Schema::dropIfExists('Pendidikan_1104');
        Schema::dropIfExists('Portofolio_1104');
    }
};
