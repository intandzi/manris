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
        Schema::create('pemantauan_kajians', function (Blueprint $table) {
            $table->bigIncrements('pemantauanKajian_id');
            $table->bigInteger('perlakuanRisiko_id')->unsigned()->nullable();
            $table->foreign('perlakuanRisiko_id')->references('perlakuanRisiko_id')->on('perlakuan_risikos')->onDelete('cascade');
            $table->text('pemantauanKajian_pemantauan')->nullable(true);
            $table->text('pemantauanKajian_kajian')->nullable(true);
            $table->string('pemantauanKajian_buktiPemantauan')->nullable(true);
            $table->string('pemantauanKajian_buktiKajian')->nullable(true);
            $table->integer('pemantauanKajian_freqPemantauan')->nullable(true);
            $table->integer('pemantauanKajian_freqPelaporan')->nullable(true);
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
        Schema::dropIfExists('pemantauan_kajians');
    }
};
