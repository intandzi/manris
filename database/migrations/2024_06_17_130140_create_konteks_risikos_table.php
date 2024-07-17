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
        Schema::create('konteks_risikos', function (Blueprint $table) {
            $table->bigIncrements('konteks_id');
            $table->bigInteger('kpi_id')->unsigned()->nullable();
            $table->foreign('kpi_id')->references('kpi_id')->on('k_p_i_s')->onDelete('cascade');
            $table->string('konteks_kode')->nullable(false);
            $table->text('konteks_desc')->nullable(false);
            $table->enum('konteks_kategori', ['internal', 'external'])->nullable(false);
            $table->boolean('konteks_lockStatus')->nullable(false)->default(false);
            $table->boolean('konteks_activeStatus')->nullable(false)->default(true);
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
        Schema::dropIfExists('konteks_risikos');
    }
};
