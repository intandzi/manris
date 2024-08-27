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
        Schema::create('komunikasi_stakeholders', function (Blueprint $table) {
            $table->bigIncrements('komunikasiStakeholder_id');
            $table->bigInteger('komunikasi_id')->unsigned()->nullable();
            $table->foreign('komunikasi_id')->references('komunikasi_id')->on('komunikasis')->onDelete('cascade');
            $table->bigInteger('stakeholder_id')->unsigned()->nullable();
            $table->foreign('stakeholder_id')->references('stakeholder_id')->on('stakeholders')->onDelete('cascade');
            $table->string('komunikasiStakeholder_ket')->nullable(false);
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
        Schema::dropIfExists('komunikasi_stakeholders');
    }
};
