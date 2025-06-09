<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatorIdAndIsPublicToThemes extends Migration
{
    public function up()
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->foreignId('creator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_public')->default(false);
        });
    }

    public function down()
    {
        Schema::table('themes', function (Blueprint $table) {
            $table->dropForeign(['creator_id']);
            $table->dropColumn(['creator_id', 'is_public']);
        });
    }
}