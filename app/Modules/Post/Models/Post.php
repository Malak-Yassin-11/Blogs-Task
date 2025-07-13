<?php

namespace App\Modules\Post\Models;

class Post {

    public int $id;
    public int $user_id;
    public string $title;
    public string $content;
    public ?string $image;
    public string $created_at;
    public string $updated_at;
}