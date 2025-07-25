<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('peringkat', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('label');
            $table->integer('tingkat');
            $table->string('color')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('peringkat');
    }
};
