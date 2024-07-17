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
        Schema::create('kriteria_deteksi_kegagalans', function (Blueprint $table) {
            $table->bigIncrements('deteksiKegagalan_id');
            $table->bigInteger('risk_id')->unsigned()->nullable();
            $table->foreign('risk_id')->references('risk_id')->on('risks')->onDelete('cascade');
            $table->integer('deteksiKegagalan_scale')->nullable(false);
            $table->text('deteksiKegagalan_desc')->nullable(false);
            $table->boolean('deteksiKegagalan_lockStatus')->nullable(false)->default(false);
            $table->boolean('deteksiKegagalan_activeStatus')->nullable(false)->default(true);
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
        Schema::dropIfExists('kriteria_deteksi_kegagalans');
    }
};
