<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');         // Recipient of the notification
            $table->foreignId('actor_id')->constrained('users')->onDelete('cascade'); // User who triggered the action
            $table->string('type');                                                   // e.g., 'like', 'follow', 'message'
            $table->unsignedBigInteger('subject_id')->nullable();                     // ID of post, conversation, etc.
            $table->string('subject_type')->nullable();                               // e.g., 'App\Models\Post', 'App\Models\Conversation'
            $table->text('message')->nullable();                                      // Optional: e.g., "Bob liked your post"
            $table->boolean('read')->default(false);                                  // Whether the notification is read
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
