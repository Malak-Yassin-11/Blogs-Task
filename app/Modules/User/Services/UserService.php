<?php

namespace App\Modules\User\Services;

use App\Modules\User\Models\User;
use PDO;

class UserService {

    public function __construct(private PDO $pdo) {

    }

    public function getPDO(): PDO {
        
        return $this->pdo;
    }

    public function create(User $user): bool {
        
        $query = $this->pdo->prepare(
            "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)"
        );

        return $query->execute([
            'name' => $user->name,
            'email' => $user->email,
            'password' => password_hash($user->password, PASSWORD_BCRYPT),
        ]);
    }

    public function listAllUsers() {

        $query = $this->pdo->prepare("SELECT * FROM users");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
        // return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateUserById(int $id, array $data): void {

        $fields = [];
        $params = [];

        if (!empty($data['name'])) {
            $fields[] = "name = ?";
            $params[] = $data['name'];
        }

        if (!empty($data['email'])) {
            $fields[] = "email = ?";
            $params[] = $data['email'];
        }

        if (empty($fields)) {
            echo "No data to update." . PHP_EOL;
            return;
        }

        $params[] = $id;

        $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
        $query = $this->pdo->prepare($sql);
        $query->execute($params);
    }

    public function deleteUserById(int $id): void {

        $this->pdo->beginTransaction();

        try {
            $deletePostsQuery = $this->pdo->prepare("DELETE FROM posts WHERE user_id = ?");
            $deletePostsQuery->execute([$id]);

            $deleteUserQuery = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
            $deleteUserQuery->execute([$id]);

            $this->pdo->commit();
            
            echo "User and all associated posts deleted successfully." . PHP_EOL;
        } catch (\Exception $e) {
            $this->pdo->rollBack();
            echo "Error deleting user: " . $e->getMessage() . PHP_EOL;
        }
    }

    public function findUserById(int $id): ?array {

        $query = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $query->execute([$id]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        return $user ?: null;
    }

    public function getUserWithPosts(int $id): ?array {

        $userQuery = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $userQuery->execute([$id]);
        $user = $userQuery->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return null;
        }

        $postsQuery = $this->pdo->prepare("SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC");
        $postsQuery->execute([$id]);
        $posts = $postsQuery->fetchAll(PDO::FETCH_ASSOC);

        return [
            'user' => $user,
            'posts' => $posts
        ];
    }

}