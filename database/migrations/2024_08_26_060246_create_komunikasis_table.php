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
        Schema::create('komunikasis', function (Blueprint $table) {
            $table->bigIncrements('komunikasi_id');
            $table->bigInteger('risk_id')->unsigned()->nullable();
            $table->foreign('risk_id')->references('risk_id')->on('risks')->onDelete('cascade');
            $table->text('komunikasi_tujuan')->nullable(false);
            $table->text('komunikasi_konten')->nullable(false);
            $table->text('komunikasi_media')->nullable(false);
            $table->text('komunikasi_metode')->nullable(false);
            $table->text('komunikasi_pemilihanWaktu')->nullable(false);
            $table->text('komunikasi_frekuensi')->nullable(false);
            $table->boolean('komunikasi_lockStatus')->default(false)->nullable(false);
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
        Schema::dropIfExists('komunikasis');
    }
};
