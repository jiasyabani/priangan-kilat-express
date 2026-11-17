
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_status_pakets', function (Blueprint $table) {
            $table->id();

            // Paket yang mengalami perubahan status
            $table->foreignId('paket_id')
                ->constrained('pakets')
                ->cascadeOnDelete();

            // Status sebelum dan sesudah perubahan
            $table->string('status_sebelumnya', 30)->nullable();
            $table->string('status_baru', 30);

            // Pelaku perubahan
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum('pelaku', ['Admin', 'Kurir']);

            // Catatan perubahan
            $table->text('catatan')->nullable();

            $table->timestamp('waktu_perubahan')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_status_pakets');
    }
};
