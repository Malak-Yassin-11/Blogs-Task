<?php

namespace App\Modules\Post\Controllers;

use App\Modules\Post\Requests\CreatePostRequest;
use App\Modules\Post\Services\PostService;
use App\Modules\User\Services\UserService;

class PostController {

    public function __construct(
        private PostService $postService,
        private UserService $userService
    ) {}

    public function create(array $data) {

        $user = $this->userService->findUserById($data['user_id']);

        if (!$user) {
            echo "User not found with ID: {$data['user_id']}" . PHP_EOL;
            return;
        }

        $errors = CreatePostRequest::validate($data);

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . PHP_EOL;
            }
            return;
        }

        $this->postService->createPost($data);
        echo "Post created successfully." . PHP_EOL;
    }

}