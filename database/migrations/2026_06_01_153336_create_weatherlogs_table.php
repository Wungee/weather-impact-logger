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
    Schema::create('weather_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('site_id')->constrained()->onDelete('cascade');
        $table->date('date');
        $table->string('condition'); // rainy, sunny, windy, overcast, cloudy
        $table->decimal('temperature', 5, 2)->nullable();
        $table->decimal('precipitation', 5, 2)->nullable();
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weatherlogs');
    }
};
