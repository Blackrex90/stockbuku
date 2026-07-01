<?php
// inc/upload_helper.php
// Helper for validating and storing uploaded images safely

function validate_and_store_image(array $file, string $destDir, int $maxSize = 2097152): string {
    // $file is from $_FILES['...']
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('No file uploaded or upload error.');
    }

    // Validate size
    if ($file['size'] > $maxSize) {
        throw new RuntimeException('File size exceeds limit of ' . ($maxSize / 1024 / 1024) . 'MB.');
    }

    // Validate MIME type using finfo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif'
    ];

    if (!array_key_exists($mime, $allowed)) {
        throw new RuntimeException('Invalid image type.');
    }

    $ext = $allowed[$mime];

    // Ensure destination directory exists
    if (!is_dir($destDir)) {
        if (!mkdir($destDir, 0755, true)) {
            throw new RuntimeException('Failed to create destination directory.');
        }
    }

    // Generate secure random filename
    $filename = bin2hex(random_bytes(16)) . '.' . $ext;
    $destination = rtrim($destDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new RuntimeException('Failed to move uploaded file.');
    }

    // Optional: set restrictive permissions
    @chmod($destination, 0644);

    return $filename;
}
