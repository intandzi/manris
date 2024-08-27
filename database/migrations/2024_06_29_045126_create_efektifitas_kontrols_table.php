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
        Schema::create('efektifitas_kontrols', function (Blueprint $table) {
            $table->bigIncrements('efektifitasKontrol_id');
            $table->bigInteger('risk_id')->unsigned()->nullable();
            $table->foreign('risk_id')->references('risk_id')->on('risks')->onDelete('cascade');
            $table->bigInteger('controlRisk_id')->unsigned()->nullable();
            $table->foreign('controlRisk_id')->references('controlRisk_id')->on('control_risks')->onDelete('cascade');
            $table->boolean('efektifitasKontrol_kontrolStatus')->default(false)->nullable(false);
            $table->text('efektifitasKontrol_kontrolDesc')->nullable(false);
            $table->integer('efektifitasKontrol_totalEfektifitas')->nullable(false);
            $table->boolean('efektifitasKontrol_lockStatus')->default(false)->nullable(false);
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
        Schema::dropIfExists('efektifitas_kontrols');
    }
};
