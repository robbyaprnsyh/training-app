<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parameter', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->string('name');
            $table->enum('tipe_penilaian', ['KUANTITATIF', 'KUALITATIF']);
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('parameter_kuantitatif', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parameter_id');
            $table->string('operator_min')->nullable();
            $table->float('nilai_min')->nullable();
            $table->string('operator_max')->nullable();
            $table->float('nilai_max')->nullable();
            $table->uuid('peringkat_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parameter_id')->references('id')->on('parameter')->onDelete('cascade');
            $table->foreign('peringkat_id')->references('id')->on('peringkat')->onDelete('cascade');
        });

        Schema::create('parameter_kualitatif', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parameter_id');
            $table->text('analisa_default')->nullable();
            $table->uuid('peringkat_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('parameter_id')->references('id')->on('parameter')->onDelete('cascade');
            $table->foreign('peringkat_id')->references('id')->on('peringkat')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameter_kualitatif');
        Schema::dropIfExists('parameter_kuantitatif');
        Schema::dropIfExists('parameter');
    }
};
