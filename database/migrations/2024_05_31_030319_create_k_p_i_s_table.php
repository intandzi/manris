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
        Schema::create('k_p_i_s', function (Blueprint $table) {
            $table->bigIncrements('kpi_id');
            $table->bigInteger('unit_id')->unsigned()->nullable();
            $table->foreign('unit_id')->references('unit_id')->on('units')->onDelete('cascade');
            $table->bigInteger('kategoriStandar_id')->unsigned()->nullable();
            $table->foreign('kategoriStandar_id')->references('kategoriStandar_id')->on('kategori_standars')->onDelete('cascade');
            $table->string('kpi_kode')->nullable(false);
            $table->string('kpi_nama')->nullable(false);
            $table->date('kpi_tanggalMulai')->nullable(false);
            $table->date('kpi_tanggalAkhir')->nullable(false);
            $table->string('kpi_periode')->nullable(false);
            $table->text('kpi_kategoriKinerja')->nullable(false);
            $table->text('kpi_indikatorKinerja')->nullable(false);
            $table->text('kpi_dokumenPendukung')->nullable(false);
            $table->boolean('kpi_lockStatus')->default(false);
            $table->boolean('kpi_sendUMRStatus')->default(false);
            $table->boolean('kpi_activeStatus')->default(true);
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
        Schema::dropIfExists('k_p_i_s');
    }
};
