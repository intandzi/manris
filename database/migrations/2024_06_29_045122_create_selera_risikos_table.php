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
        Schema::create('selera_risikos', function (Blueprint $table) {
            $table->bigIncrements('seleraRisiko_id');
            $table->bigInteger('derajatRisiko_id')->unsigned()->nullable();
            $table->foreign('derajatRisiko_id')->references('derajatRisiko_id')->on('derajat_risikos')->onDelete('cascade');
            $table->text('seleraRisiko_desc')->nullable(true);
            $table->text('seleraRisiko_tindakLanjut')->nullable(true);
            $table->boolean('seleraRisiko_activeStatus')->default(true)->nullable(true);
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
        Schema::dropIfExists('selera_risikos');
    }
};
