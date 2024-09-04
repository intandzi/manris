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
        Schema::create('penilaian_efektifitas', function (Blueprint $table) {
            $table->bigIncrements('penilaianEfektifitas_id');
            $table->text('penilaianEfektifitas_pertanyaan')->nullable(false);
            $table->integer('penilaianEfektifitas_ya')->nullable(false);
            $table->integer('penilaianEfektifitas_sebagian')->nullable(false);
            $table->integer('penilaianEfektifitas_tidak')->nullable(false);
            $table->boolean('penilaianEfektifitas_activeStatus')->default(true)->nullable(false);
            $table->char('created_by', 3)->nullable();
            $table->char('updated_by', 3)->nullable();
            $table->char('deleted_by', 3)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_efektifitas');
    }
};
