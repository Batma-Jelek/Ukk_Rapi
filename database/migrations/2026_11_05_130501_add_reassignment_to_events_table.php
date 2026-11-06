<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->text('reassignment_reason')->nullable()->after('status');
            $table->foreignId('moved_by_user_id')
                ->nullable()
                ->after('reassignment_reason')
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropConstrainedForeignId('moved_by_user_id');
            $table->dropColumn('reassignment_reason');
        });
    }
};