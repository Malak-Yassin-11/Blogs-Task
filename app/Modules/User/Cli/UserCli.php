<?php

namespace App\Modules\User\Cli;

class UserCli {

    public static function createUser($controller) {

        echo "Enter name: ";
        $name = trim(fgets(STDIN));

        echo "Enter email: ";
        $email = trim(fgets(STDIN));

        echo "Enter password: ";
        $password = trim(fgets(STDIN));

        $controller->create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);
    }

    public static function updateUser($controller) {

        echo "Enter user ID to update: ";
        $id = trim(fgets(STDIN));

        if (!is_numeric($id) || (int)$id <= 0) {
            echo "Invalid ID. Must be a positive integer." . PHP_EOL;
            return;
        }

        $id = (int)$id;

        $user = $controller->getUserById($id);

        if (!$user) {
            echo "User not found." . PHP_EOL;
            return;
        }

        echo "Current name: {$user['name']}" . PHP_EOL;
        echo "Enter new name (or press Enter to keep it): ";
        $name = trim(fgets(STDIN));

        echo "Current email: {$user['email']}" . PHP_EOL;
        echo "Enter new email (or press Enter to keep it): ";
        $email = trim(fgets(STDIN));

        $data = [];

        if ($name !== '') {
            $data['name'] = $name;
        }

        if ($email !== '') {
            $data['email'] = $email;
        }

        if (empty($data)) {
            echo " No changes made." . PHP_EOL;
            return;
        }

        $controller->update($id, $data);
    }

    public static function deleteUser($controller) {

        echo "Enter user ID to delete: ";
        $id = trim(fgets(STDIN));

        if (!is_numeric($id) || (int)$id <= 0) {
            echo "Invalid ID. Must be a positive integer." . PHP_EOL;
            return;
        }

        $id = (int)$id;

        $user = $controller->getUserById($id);

        if (!$user) {
            echo "User not found." . PHP_EOL;
            return;
        }

        echo "Are you sure you want to delete user '{$user['name']}' and all their posts? (y/N): ";
        $confirmation = trim(fgets(STDIN));

        if (strtolower($confirmation) === 'y' || strtolower($confirmation) === 'yes') {
            $controller->delete($id);
        } else {
            echo "Deletion cancelled." . PHP_EOL;
        }
    }

    public static function getUserWithPosts($controller) {

        echo "Enter user ID to view with posts: ";
        $id = trim(fgets(STDIN));

        if (!is_numeric($id) || (int)$id <= 0) {
            echo "Invalid ID. Must be a positive integer." . PHP_EOL;
            return;
        }

        $id = (int)$id;
        $controller->getUserWithPosts($id);
    }
}
