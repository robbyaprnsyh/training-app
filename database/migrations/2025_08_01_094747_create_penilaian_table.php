<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parameter_id');
            $table->uuid('peringkat_id');
            $table->integer('nilai');
            $table->text('analisa');
            $table->boolean('status');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('parameter_id')->references('id')->on('parameter')->onDelete('cascade');
            $table->foreign('peringkat_id')->references('id')->on('peringkat')->onDelete('cascade');
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
