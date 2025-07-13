<?php

namespace App\Modules\User\Controllers;

use App\Modules\User\Models\User;
use App\Modules\User\Requests\CreateUserRequest;
use App\Modules\User\Requests\UpdateUserRequest;
use App\Modules\User\Services\UserService;

class UserController {

    public function __construct(private UserService $userService) {

    }

    public function create(array $request) {

        $errors = CreateUserRequest::validate($request, $this->getPDO());
        
        if (!empty($errors)) {
            foreach ($errors as $field => $message) {
                echo $message . PHP_EOL;
            }
            return;
        }

        $user = new User();
        $user->name = $request['name'];
        $user->email = $request['email'];
        $user->password = $request['password'];

        $this->userService->create($user)
            ? print("User created successfully!" . PHP_EOL)
            : print("Failed to create user." . PHP_EOL);
    }

    public function listAllUsers() {

        $users = $this->userService->listAllUsers();
        
        if (empty($users)) {
            echo "No users found in the database." . PHP_EOL;
            return;
        }

        echo PHP_EOL;
        echo "========= All Users =========" . PHP_EOL;
        foreach ($users as $user) {
            echo "ID: {$user->id}, Name: {$user->name}, Email: {$user->email}" . PHP_EOL;
            // echo "ID: {$user['id']}, Name: {$user['name']}, Email: {$user['email']}" . PHP_EOL;
        }
        echo "=============================" . PHP_EOL;
    }

    public function update(int $id, array $data) {

        $errors = UpdateUserRequest::validate($data, $this->getPDO(), $id);

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo $error . PHP_EOL;
            }
            return;
        }

        $this->userService->updateUserById($id, $data);
        echo "User updated successfully." . PHP_EOL;
    }

    public function delete(int $id) {

        $this->userService->deleteUserById($id);
    }

    public function getUserWithPosts(int $id) {

        $result = $this->userService->getUserWithPosts($id);

        if (!$result) {
            echo "User not found with ID: {$id}" . PHP_EOL;
            return;
        }

        $user = $result['user'];
        $posts = $result['posts'];

        echo PHP_EOL;
        echo "========= User Information =========" . PHP_EOL;
        echo "ID: {$user['id']}" . PHP_EOL;
        echo "Name: {$user['name']}" . PHP_EOL;
        echo "Email: {$user['email']}" . PHP_EOL;
        echo "Created: {$user['created_at']}" . PHP_EOL;
        echo "Updated: {$user['updated_at']}" . PHP_EOL;

        echo PHP_EOL;
        echo "========= Posts ({$user['name']}) =========" . PHP_EOL;
        
        if (empty($posts)) {
            echo "No posts found for this user." . PHP_EOL;
        } else {
            foreach ($posts as $post) {
                echo PHP_EOL;
                echo "Post ID: {$post['id']}" . PHP_EOL;
                echo "Title: {$post['title']}" . PHP_EOL;
                echo "Content: " . substr($post['content'], 0, 100) . (strlen($post['content']) > 100 ? "..." : "") . PHP_EOL;
                echo "Image: {$post['image']}" . PHP_EOL;
                echo "Created: {$post['created_at']}" . PHP_EOL;
                echo "Updated: {$post['updated_at']}" . PHP_EOL;
                echo "----------------------------------------" . PHP_EOL;
            }
        }
    }

    public function getPDO() {

        return $this->userService->getPDO();
    }

    public function getUserById(int $id): ?array {
        
        return $this->userService->findUserById($id);
    }

}