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
        Schema::table('attachment_messages', function (Blueprint $table) {
            $table->foreignId('ticket_message_id')
                  ->after('id')
                  ->constrained('ticket_messages')
                  ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('attachment_messages', function (Blueprint $table) {
            $table->dropForeign(['ticket_message_id']);
            $table->dropColumn('ticket_message_id');
        });
    }
};
