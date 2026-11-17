
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setoran_cods', function (Blueprint $table) {
            $table->id();

            // Paket COD yang disetorkan
            $table->foreignId('paket_id')
                ->unique()
                ->constrained('pakets')
                ->restrictOnDelete();

            // Admin yang mencatat setoran
            $table->foreignId('admin_id')
                ->constrained('users')
                ->restrictOnDelete();

            // Jumlah COD yang disetorkan
            $table->decimal('jumlah_setoran', 12, 2);

            // Waktu pencatatan setoran
            $table->timestamp('tanggal_setoran')->useCurrent();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setoran_cods');
    }
};
