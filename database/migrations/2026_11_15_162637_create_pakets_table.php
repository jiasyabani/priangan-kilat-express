
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pakets', function (Blueprint $table) {
            $table->id();

            // Nomor resi
            $table->string('no_resi', 25)->unique();

            // Relasi pelanggan dan kurir
            $table->foreignId('pengirim_id')->constrained('pelanggans')->restrictOnDelete();
            $table->foreignId('kurir_id')->nullable()->constrained('kurirs')->nullOnDelete();

            // Data penerima
            $table->string('nama_penerima', 150);
            $table->string('no_telepon_penerima', 20);
            $table->text('alamat_penerima');

            // Tujuan dan zona
            $table->string('kota_tujuan', 100);
            $table->unsignedTinyInteger('zona');
            $table->boolean('zona_manual')->default(false);

            // Berat dan layanan
            $table->decimal('berat', 8, 2)->nullable();
            $table->boolean('perlu_verifikasi')->default(false);
            $table->enum('layanan', ['Reguler', 'Express']);

            // Salinan tarif dan ongkir saat transaksi
            $table->decimal('tarif_per_kg', 12, 2);
            $table->decimal('ongkir', 12, 2);

            // COD
            $table->decimal('nilai_cod', 12, 2)->default(0);
            $table->decimal('biaya_cod', 12, 2)->default(0);
            $table->boolean('cod_disetor')->default(false);

            // Status pengiriman
            $table->enum('status', [
                'Diterima',
                'Transit',
                'Dikirim',
                'Terkirim',
                'Gagal Kirim',
                'Retur'
            ])->default('Diterima');

            $table->unsignedTinyInteger('jumlah_gagal')->default(0);
            $table->dateTime('tanggal_masuk');

            $table->timestamps();

            $table->index(['status', 'tanggal_masuk']);
            $table->index(['kurir_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pakets');
    }
};
