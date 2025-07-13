<?php

namespace App\Modules\User\Requests;

use PDO;

class CreateUserRequest {

    public static function validate(array $data, PDO $pdo, ?int $userId = null): array {
        
        $errors = [];

        if(!empty($data["name"])) {
            if (empty($data['name']) || strlen($data['name']) < 3) {
                $errors['name'] = 'Name must be at least 3 characters.';
            } elseif (!preg_match('/^[a-zA-Z\s]+$/', $data['name'])) {
                $errors['name'] = 'Name must contain only letters and spaces.';
            }
        }

        if (!empty($data['email'])) {
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Email must be a valid email address.';
            } else {
                $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?" . ($userId ? " AND id != ?" : ""));
                $stmt->execute($userId ? [$data['email'], $userId] : [$data['email']]);
                if ($stmt->fetchColumn() > 0) {
                    $errors['email'] = 'This email is already taken.';
                }
            }
        }

        if(static::shouldValidatePassword()) {
            if (empty($data['password']) || strlen($data['password']) < 6) {
                $errors['password'] = 'Password must be at least 6 characters.';
            } else {
                $password = $data['password'];
                if (!preg_match('/[A-Z]/', $password)) {
                    $errors['password'] = 'Password must contain at least one uppercase letter.';
                } elseif (!preg_match('/[a-z]/', $password)) {
                    $errors['password'] = 'Password must contain at least one lowercase letter.';
                } elseif (!preg_match('/[0-9]/', $password)) {
                    $errors['password'] = 'Password must contain at least one number.';
                } elseif (!preg_match('/[\W_]/', $password)) {
                    $errors['password'] = 'Password must contain at least one special character.';
                }
            }
        }

        return $errors;
    }

    protected static function shouldValidatePassword(): bool {

        return true;
    }
}