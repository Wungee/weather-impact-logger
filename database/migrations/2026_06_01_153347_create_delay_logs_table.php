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
    Schema::create('delay_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('site_id')->constrained()->onDelete('cascade');
        $table->date('date');
        $table->decimal('hours_delayed', 5, 2);
        $table->string('reason')->nullable(); // weather, supply, labor, equipment
        $table->text('notes')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delay_logs');
    }
};
