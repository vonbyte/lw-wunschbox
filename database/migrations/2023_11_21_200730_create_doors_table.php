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
        Schema::create('doors', function (Blueprint $table) {
            $table->id();
            $table->text('intro_text')->nullable();
            $table->string('media')->nullable();
            $table->unsignedSmallInteger('media_type')->default(0);
            $table->text('content');
            $table->text('question');
            $table->text('answer')->nullable();
            $table->unsignedSmallInteger('sortnr');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doors');
    }
};
