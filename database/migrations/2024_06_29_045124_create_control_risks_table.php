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
        Schema::create('control_risks', function (Blueprint $table) {
            $table->bigIncrements('controlRisk_id');
            $table->bigInteger('risk_id')->unsigned()->nullable();
            $table->foreign('risk_id')->references('risk_id')->on('risks')->onDelete('cascade');
            $table->bigInteger('kemungkinan_id')->unsigned()->nullable();
            $table->foreign('kemungkinan_id')->references('kemungkinan_id')->on('kriteria_kemungkinans')->onDelete('cascade');
            $table->bigInteger('dampak_id')->unsigned()->nullable();
            $table->foreign('dampak_id')->references('dampak_id')->on('kriteria_dampaks')->onDelete('cascade');
            $table->bigInteger('deteksiKegagalan_id')->unsigned()->nullable();
            $table->foreign('deteksiKegagalan_id')->references('deteksiKegagalan_id')->on('kriteria_deteksi_kegagalans')->onDelete('cascade');
            $table->integer('controlRisk_RPN')->nullable(false);
            $table->bigInteger('derajatRisiko_id')->unsigned()->nullable();
            $table->foreign('derajatRisiko_id')->references('derajatRisiko_id')->on('derajat_risikos')->onDelete('cascade');
            $table->bigInteger('seleraRisiko_id')->unsigned()->nullable();
            $table->foreign('seleraRisiko_id')->references('seleraRisiko_id')->on('selera_risikos')->onDelete('cascade');
            $table->enum('controlRisk_RTM', ['RTM', 'No RTM'])->default('No RTM')->nullable(true);
            $table->enum('controlRisk_efektivitas',  ['efektif', 'tidak efektif'])->nullable(true);
            $table->boolean('controlRisk_isControl')->default(false)->nullable(false);
            $table->boolean('controlRisk_lockStatus')->default(false)->nullable(false);
            $table->char('created_by', 1)->nullable();
            $table->char('updated_by', 1)->nullable();
            $table->char('deleted_by', 1)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('control_risks');
    }
};
