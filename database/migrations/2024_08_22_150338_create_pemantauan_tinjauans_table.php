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
        Schema::create('pemantauan_tinjauans', function (Blueprint $table) {
            $table->bigIncrements('pemantauanTinjauan_id');
            $table->bigInteger('perlakuanRisiko_id')->unsigned()->nullable();
            $table->foreign('perlakuanRisiko_id')->references('perlakuanRisiko_id')->on('perlakuan_risikos')->onDelete('cascade');
            $table->text('pemantauanTinjauan_pemantauanDesc')->nullable(true);
            $table->text('pemantauanTinjauan_tinjauanDesc')->nullable(true);
            $table->text('pemantauanTinjauan_buktiPemantauan')->nullable(true);
            $table->text('pemantauanTinjauan_buktiTinjauan')->nullable(true);
            $table->text('pemantauanTinjauan_freqPemantauan')->nullable(true);
            $table->text('pemantauanTinjauan_freqPelaporan')->nullable(true);
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
        Schema::dropIfExists('pemantauan_tinjauans');
    }
};
