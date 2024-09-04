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
        Schema::create('perlakuan_risikos', function (Blueprint $table) {
            $table->bigIncrements('perlakuanRisiko_id');
            $table->bigInteger('controlRisk_id')->unsigned()->nullable();
            $table->foreign('controlRisk_id')->references('controlRisk_id')->on('control_risks')->onDelete('cascade');
            $table->bigInteger('jenisPerlakuan_id')->unsigned()->nullable();
            $table->foreign('jenisPerlakuan_id')->references('jenisPerlakuan_id')->on('jenis_perlakuans')->onDelete('cascade');
            $table->boolean('perlakuanRisiko_lockStatus')->default(false)->nullable(false);
            $table->boolean('pemantauanKajian_lockStatus')->default(false)->nullable(false);
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
        Schema::dropIfExists('perlakuan_risikos');
    }
};
