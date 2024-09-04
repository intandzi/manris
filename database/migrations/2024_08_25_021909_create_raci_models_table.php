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
        Schema::create('raci_models', function (Blueprint $table) {
            $table->bigIncrements('raci_id');
            $table->bigInteger('risk_id')->unsigned()->nullable();
            $table->foreign('risk_id')->references('risk_id')->on('risks')->onDelete('cascade');
            $table->bigInteger('controlRisk_id')->unsigned()->nullable();
            $table->foreign('controlRisk_id')->references('controlRisk_id')->on('control_risks')->onDelete('cascade');
            $table->bigInteger('stakeholder_id')->unsigned()->nullable();
            $table->foreign('stakeholder_id')->references('stakeholder_id')->on('stakeholders')->onDelete('cascade');
            // $table->unique(['risk_id', 'controlRisk_id', 'stakeholder_id']);
            $table->char('raci_desc')->nullable(false);
            $table->boolean('raci_lockStatus')->default(false)->nullable(false);
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
        Schema::dropIfExists('raci_models');
    }
};
