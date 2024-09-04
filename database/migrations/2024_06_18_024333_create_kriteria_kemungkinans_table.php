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
        Schema::create('kriteria_kemungkinans', function (Blueprint $table) {
            $table->bigIncrements('kemungkinan_id');
            $table->bigInteger('risk_id')->unsigned()->nullable();
            $table->foreign('risk_id')->references('risk_id')->on('risks')->onDelete('cascade');
            $table->integer('kemungkinan_scale')->nullable(false);
            $table->text('kemungkinan_desc')->nullable(false);
            $table->boolean('kemungkinan_lockStatus')->nullable(false)->default(false);
            $table->boolean('kemungkinan_activeStatus')->nullable(false)->default(true);
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
        Schema::dropIfExists('kriteria_kemungkinans');
    }
};
