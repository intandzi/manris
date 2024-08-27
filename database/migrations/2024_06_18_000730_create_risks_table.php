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
        Schema::create('risks', function (Blueprint $table) {
            $table->bigIncrements('risk_id');
            $table->bigInteger('konteks_id')->unsigned()->nullable();
            $table->foreign('konteks_id')->references('konteks_id')->on('konteks_risikos')->onDelete('cascade');
            $table->string('risk_kode')->nullable(false);
            $table->text('risk_riskDesc')->nullable(false);
            $table->string('risk_penyebabKode')->nullable(false);
            $table->text('risk_penyebab')->nullable(false);
            $table->boolean('risk_lockStatus')->nullable(false)->default(false);
            $table->boolean('risk_kriteriaLockStatus')->nullable(false)->default(false);
            $table->boolean('risk_allPhaseLockStatus')->nullable(false)->default(false);
            $table->boolean('risk_isSendUMR')->nullable(false)->default(false);
            $table->boolean('risk_validateRiskRegister')->nullable(false)->default(false);
            $table->boolean('risk_validateRiskControl')->nullable(false)->default(false);
            $table->boolean('risk_activeStatus')->nullable(false)->default(true);
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
        Schema::dropIfExists('risks');
    }
};
