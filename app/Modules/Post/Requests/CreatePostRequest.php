<?php

namespace App\Modules\Post\Requests;

class CreatePostRequest {

    public static function validate(array $data): array {
        
        $errors = [];

        if (empty($data['title']) || strlen($data['title']) < 3) {
            $errors[] = 'Title is required and must be at least 3 characters.';
        }

        if (empty($data['content']) || strlen($data['content']) < 10) {
            $errors[] = 'Content must be at least 10 characters.';
        }

        if (empty($data['image_path']) || !file_exists($data['image_path'])) {
            $errors[] = 'Image file does not exist.';
        } else {
            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            $extension = strtolower(pathinfo($data['image_path'], PATHINFO_EXTENSION));

            if (!in_array($extension, $allowedExtensions)) {
                $errors[] = 'Image must be a PNG, JPG, or JPEG file.';
            }

            $fileSize = filesize($data['image_path']);
            if ($fileSize > 10 * 1024 * 1024) {
                $errors[] = 'Image must be less than 10MB.';
            }
        }

        return $errors;
    }
}