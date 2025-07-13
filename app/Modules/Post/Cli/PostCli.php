<?php

namespace App\Modules\Post\Cli;

use App\Modules\User\Controllers\UserController;

class PostCli {
    
    public static function createPost($controller) {

        echo "Enter user ID: ";
        $userId = trim(fgets(STDIN));

        if (!is_numeric($userId) || (int)$userId <= 0) {
            echo "Invalid user ID. Must be a positive integer." . PHP_EOL;
            return;
        }

        echo "Enter post title: ";
        $title = trim(fgets(STDIN));

        echo "Enter post content: ";
        $content = trim(fgets(STDIN));

        $imageFiles = glob("test-images/*.{jpg,jpeg,png}", GLOB_BRACE);

        if (empty($imageFiles)) {
            echo "No images found in test-images folder." . PHP_EOL;
            return;
        }

        echo "Choose an image from the list below:" . PHP_EOL;
        foreach ($imageFiles as $index => $file) {
            echo ($index + 1) . ". " . basename($file) . PHP_EOL;
        }

        echo "Your choice (number): ";
        $choice = trim(fgets(STDIN));

        $selectedPath = $imageFiles[$choice - 1] ?? null;

        if (!$selectedPath || !file_exists($selectedPath)) {
            echo "Invalid selection or file doesn't exist." . PHP_EOL;
            return;
        }

        $controller->create([
            'user_id' => (int)$userId,
            'title' => $title,
            'content' => $content,
            'image_path' => $selectedPath,
        ]);
    }
}