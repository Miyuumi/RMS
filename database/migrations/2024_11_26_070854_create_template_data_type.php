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
        Schema::create('template_data_type', function (Blueprint $table) {
            $table->id();
            $table->integer("template_id")->nullable();
            $table->text("type")->nullable();
            $table->text("default")->nullable();
            $table->text("choices")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_data_type');
    }
};
