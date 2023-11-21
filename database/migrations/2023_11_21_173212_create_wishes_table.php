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
        Schema::create('wishes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('links')->nullable();
            $table->string('image')->nullable();

            $table->text('admin_notes')->nullable();
            $table->unsignedTinyInteger('sortnr')->nullable();
            $table->unsignedTinyInteger('state')->default(1);

            $table->unsignedTinyInteger('shipping_state')->nullable();
            $table->unsignedTinyInteger('shipping_company')->nullable();
            $table->string('trackingnumber',40)->nullable();
            $table->date('delivery_date')->nullable();

            $table->foreignId('user_id')
                ->constrained('users','id', 'wishes_user_id')
                ->onDelete('cascade');
            $table->foreignId('presentee_id')
                ->constrained('users','id', 'wishes_presentee_id')
                ->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishes');
    }
};
