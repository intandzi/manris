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
        Schema::create('history_pengembalians', function (Blueprint $table) {
            $table->bigIncrements('historyPengembalian_id');
            $table->bigInteger('konteks_id')->unsigned()->nullable();
            $table->foreign('konteks_id')->references('konteks_id')->on('konteks_risikos')->onDelete('cascade');
            $table->date('historyPengembalian_tgl')->nullable(false);
            $table->text('historyPengembalian_alasan')->nullable(false);
            $table->boolean('historyPengembalian_isRiskRegister')->default(false)->nullable(true);
            $table->boolean('historyPengembalian_isRiskControl')->default(false)->nullable(true);
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
        Schema::dropIfExists('history_pengembalians');
    }
};
