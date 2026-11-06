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
    Schema::create('events', function (Blueprint $table) {
        $table->id();
        $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
        $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
        $table->string('event_name', 200);
        $table->date('date');
        $table->time('start_time');
        $table->time('end_time');
        $table->enum('status', ['pending', 'approved', 'rejected', 'moved'])->default('pending');
        $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
        $table->text('move_reason')->nullable();
        $table->timestamps();
    });
} 
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
