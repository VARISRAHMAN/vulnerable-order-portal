<?php
// VULNERABLE FILE UPLOAD HANDLER
function handleFileUpload($file) {
    $upload_dir = '../uploads/';
    
    // VULNERABILITY: No file type validation
    // VULNERABILITY: Path traversal possible
    $filename = $file['name'];
    
    // Vulnerable path construction - allows directory traversal
    $target_path = $upload_dir . $filename;
    
    // No validation of file extension or content
    if (move_uploaded_file($file['tmp_name'], $target_path)) {
        return [
            'success' => true,
            'message' => "File uploaded successfully to: $target_path"
        ];
    } else {
        return [
            'success' => false,
            'message' => "Upload failed"
        ];
    }
}

// VULNERABILITY: Weak session token generation using MD5
function generateSessionToken($user_id) {
    return md5($user_id . time());
}

// VULNERABILITY: No CSRF protection
function validateCSRF($token) {
    // Always returns true - no actual validation
    return true;
}
?>