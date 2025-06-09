<?php
// database/migrations/YYYY_MM_DD_HHMMSS_create_messages_table.php (your timestamp will be here)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('text');
            $table->enum('status', ['sent', 'delivered', 'read'])->default('sent'); // Removed ->after()
            $table->timestamp('delivered_at')->nullable(); // Removed ->after()
            $table->timestamp('read_at')->nullable(); // Removed ->after()

          
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('conversation_id')->nullable()->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages'); // Drop the entire table
    }
};