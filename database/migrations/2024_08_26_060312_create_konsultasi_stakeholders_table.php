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
        Schema::create('konsultasi_stakeholders', function (Blueprint $table) {
            $table->bigIncrements('konsultasiStakeholder_id');
            $table->bigInteger('konsultasi_id')->unsigned()->nullable();
            $table->foreign('konsultasi_id')->references('konsultasi_id')->on('konsultasis')->onDelete('cascade');
            $table->bigInteger('stakeholder_id')->unsigned()->nullable();
            $table->foreign('stakeholder_id')->references('stakeholder_id')->on('stakeholders')->onDelete('cascade');
            $table->string('konsultasiStakeholder_ket')->nullable(false);
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
        Schema::dropIfExists('konsultasi_stakeholders');
    }
};
