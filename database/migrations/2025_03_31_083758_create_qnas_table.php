<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('qnas', function (Blueprint $table) {
            $table->id();
            $table->string('question');
            $table->text('answer');
            $table->text('options')->nullable(); // JSON string or comma-separated values
            $table->string('video_link')->nullable();
            $table->text('description')->nullable();
            $table->string('link')->nullable();
            $table->boolean('randomize')->default(false);
            $table->softDeletes(); // For soft deletes
            $table->timestamps();
           
        });
    }

    public function down()
    {
        Schema::dropIfExists('qnas');
    }
};

