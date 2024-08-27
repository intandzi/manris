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
        Schema::create('detail_efektifitas_kontrols', function (Blueprint $table) {
            $table->bigIncrements('detailEfektifitasKontrol_id');
            $table->bigInteger('efektifitasKontrol_id')->unsigned()->nullable();
            $table->foreign('efektifitasKontrol_id')->references('efektifitasKontrol_id')->on('efektifitas_kontrols')->onDelete('cascade');
            $table->bigInteger('penilaianEfektifitas_id')->unsigned()->nullable();
            $table->foreign('penilaianEfektifitas_id')->references('penilaianEfektifitas_id')->on('penilaian_efektifitas')->onDelete('cascade');
            $table->integer('detailEfektifitasKontrol_skor')->nullable(true);
            $table->boolean('efektifitasKontrol_lockStatus')->default(false)->nullable(true);
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
        Schema::dropIfExists('detail_efektifitas_kontrols');
    }
};
