<?php
function handleFileUpload($file)
{
    $target_dir = dirname(__DIR__) . '/uploads/'; // Absolute path

    // Create directory if it doesn't exist
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $errors = [];
    $max_size = 5 * 1024 * 1024; // 5MB
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    // Check for errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $errors[] = "File upload error: " . $file['error'];
        return ['success' => false, 'errors' => $errors];
    }

    // Check file size
    if ($file['size'] > $max_size) {
        $errors[] = "File size exceeds maximum limit of 5MB";
    }

    // Check file type
    $file_type = mime_content_type($file['tmp_name']);
    if (!in_array($file_type, $allowed_types)) {
        $errors[] = "Only JPG, PNG, GIF, and WEBP files are allowed";
    }

    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }

    // Generate unique filename
    $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $unique_name = uniqid('img_', true) . '.' . $file_ext;
    $target_file = $target_dir . $unique_name;

    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return [
            'success' => true,
            'filename' => $unique_name,
            'path' => $target_file
        ];
    } else {
        $errors[] = "Failed to move uploaded file";
        return ['success' => false, 'errors' => $errors];
    }
}

function deleteUploadedFile($filename)
{
    if ($filename && $filename !== 'placeholder.jpg') {
        $target_dir = dirname(__DIR__) . '/uploads/';
        $file_path = $target_dir . $filename;
        if (file_exists($file_path)) {
            return unlink($file_path);
        }
    }
    return false;
}
