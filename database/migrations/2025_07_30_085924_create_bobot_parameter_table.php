<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bobot_parameter', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parameter_id');
            $table->decimal('bobot', 5, 2);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parameter_id')->references('id')->on('parameter')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bobot_parameter');
    }
};
