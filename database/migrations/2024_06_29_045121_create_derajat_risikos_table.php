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
        Schema::create('derajat_risikos', function (Blueprint $table) {
            $table->bigIncrements('derajatRisiko_id');
            $table->string('derajatRisiko_desc')->nullable(true);
            $table->integer('derajatRisiko_nilaiTingkatMin')->nullable(true);
            $table->integer('derajatRisiko_nilaiTingkatMax')->nullable(true);
            $table->boolean('derajatRisiko_activeStatus')->default(true)->nullable(true);
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
        Schema::dropIfExists('derajat_risikos');
    }
};
