<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Config\Database;
use App\Modules\Post\Cli\PostCli;
use App\Modules\Post\Controllers\PostController;
use App\Modules\Post\Services\PostService;
use App\Modules\User\Cli\UserCli;
use App\Modules\User\Controllers\UserController;
use App\Modules\User\Services\UserService;

$pdo = (new Database())->getConnection();

echo "Connected successfully to DB!" . PHP_EOL;

$userService = new UserService($pdo);
$userController = new UserController($userService);
$postService = new PostService($pdo);
$postController = new PostController($postService, $userService);

while (true) {
    echo PHP_EOL;
    echo "========= Menu =========" . PHP_EOL;
    echo "1. Create user" . PHP_EOL;
    echo "2. List all users" . PHP_EOL;
    echo "3. Update user by ID" . PHP_EOL;
    echo "4. Delete user by ID" . PHP_EOL;
    echo "5. Create post by user ID" . PHP_EOL;
    echo "6. Get user with posts by ID" . PHP_EOL;
    echo "7. Exit" . PHP_EOL;

    echo "Enter your choice: ";
    $choice = trim(fgets(STDIN));

    switch ($choice) {

        case '1':
            UserCli::createUser($userController);
            break;
        case '2':
            $userController->listAllUsers();
            break;
        case '3':
            UserCli::updateUser($userController);
            break;
        case '4':
            UserCli::deleteUser($userController);
            break;
        case '5':
            PostCli::createPost($postController);
            break;
        case '6':
            UserCli::getUserWithPosts($userController);
            break;
        case '7':
            echo "Goodbye!" . PHP_EOL;
            exit;
        default:
            echo "Invalid choice. Try again." . PHP_EOL;
    }
}
