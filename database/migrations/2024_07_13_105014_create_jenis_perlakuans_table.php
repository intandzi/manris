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
        Schema::create('jenis_perlakuans', function (Blueprint $table) {
            $table->bigIncrements('jenisPerlakuan_id');
            $table->string('jenisPerlakuan_desc')->nullable(false);
            $table->boolean('jenisPerlakuan_activeStatus')->nullable(false)->default(true);
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
        Schema::dropIfExists('jenis_perlakuans');
    }
};
