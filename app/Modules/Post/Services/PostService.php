<?php

namespace App\Modules\Post\Services;

use App\Helpers\ImageUploader;
use PDO;

class PostService {

    public function __construct(private PDO $pdo) {

    }

    public function createPost(array $data): void {

        $imageUploader = new ImageUploader();

        $storedImagePath = $imageUploader->upload($data['image_path']);

        if (!$storedImagePath) {
            echo "Failed to upload image." . PHP_EOL;
            return;
        }

        $stmt = $this->pdo->prepare("
            INSERT INTO posts (user_id, title, content, image)
            VALUES (:user_id, :title, :content, :image)
        ");

        $stmt->execute([
            'user_id' => $data['user_id'],
            'title' => $data['title'],
            'content' => $data['content'],
            'image' => $storedImagePath,
        ]);
    }

}