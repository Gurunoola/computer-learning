<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LearningContentSeeder extends Seeder
{
    public function run()
    {
        // Example data for learning contents
        $learningContents = [
            [
                'topic_id' => 2, // Assuming topic 1 exists in your topics table
                'title' => 'Introduction to Laravel',
                'type' => 'text', // type can be 'text', 'video', or 'link'
                'content' => 'Laravel is a PHP framework designed for web development...',
                'video_link' => null,  // No video link for this content
                'reference_link' => null, // No reference link
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic_id' => 2, // Assuming topic 1 exists in your topics table
                'title' => 'Laravel Video Tutorial',
                'type' => 'video',
                'content' => 'Learn the basics of Laravel by watching this video.',
                'video_link' => 'https://example.com/video.mp4', // Actual video URL
                'reference_link' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic_id' => 2, // Assuming topic 1 exists in your topics table
                'title' => 'Laravel Video Tutorial',
                'type' => 'video',
                'content' => 'Learn the basics of Laravel by watching this video.',
                'video_link' => 'https://example.com/video.mp4', // Actual video URL
                'reference_link' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'topic_id' => 3, // Assuming topic 2 exists in your topics table
                'title' => 'PHP Official Documentation',
                'type' => 'link',
                'content' => 'Access the official PHP documentation for more information.',
                'video_link' => null,
                'reference_link' => 'https://www.php.net/docs.php', // Reference link
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more contents here as needed
        ];

        // Insert the learning contents into the database
        DB::table('learning_contents')->insert($learningContents);

        $this->command->info('Learning contents table seeded!');
    }
}
