<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateThemesTable extends Migration
{
    public function up()
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('css_path')->nullable();
            $table->string('thumbnail')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('themes');
    }
}