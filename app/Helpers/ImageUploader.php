<?php

namespace App\Helpers;

class ImageUploader {

    public function upload(string $sourcePath): ?string {

        if (!file_exists($sourcePath)) {
            return null;
        }

        $extension = strtolower(pathinfo($sourcePath, PATHINFO_EXTENSION));
        $allowed = ['png', 'jpg', 'jpeg'];

        if (!in_array($extension, $allowed)) {
            return null;
        }

        $folder = "storage/" . ($extension === 'jpeg' ? 'jpeg' : $extension);

        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }

        $filename = uniqid() . '.' . $extension;
        $destination = "$folder/$filename";

        if (copy($sourcePath, $destination)) {
            return $destination;
        }

        return null;
    }
}
