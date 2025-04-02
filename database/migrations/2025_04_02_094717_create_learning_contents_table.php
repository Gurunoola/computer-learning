<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('learning_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained()->onDelete('cascade'); // Related to topics
            $table->string('title');
            $table->enum('type', ['text', 'video', 'link'])->default('text'); // Type of content
            $table->text('content')->nullable(); // Actual content data
            $table->string('video_link')->nullable(); // Video URL
            $table->string('reference_link')->nullable(); // External link
            $table->softDeletes(); // Enables soft delete
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('learning_contents');
    }
};
