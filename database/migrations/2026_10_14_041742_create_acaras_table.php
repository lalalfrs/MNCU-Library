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
        Schema::create('acaras', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category')->default('Acara Perpustakaan');
            $table->text('excerpt');
            $table->longText('description');
            $table->string('speaker')->nullable();
            $table->date('event_date');
            $table->string('start_time')->default('09:00');
            $table->string('end_time')->default('12:00');
            $table->string('location')->default('Ruang Diskusi Perpustakaan MNC University');
            $table->string('registration_link')->nullable();
            $table->unsignedInteger('quota')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acaras');
    }
};
