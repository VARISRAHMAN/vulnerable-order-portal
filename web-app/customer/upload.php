<?php
include '../includes/upload.php';

if ($_POST && $_FILES['file']) {
    $result = handleFileUpload($_FILES['file']);
    $message = $result['message'];
    $success = $result['success'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload Receipt</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="upload-container">
        <h2>Upload Receipt</h2>
        
        <?php if (isset($message)): ?>
            <div class="<?php echo $success ? 'success' : 'error'; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name="file" required>
            <button type="submit">Upload</button>
        </form>
        
        <div class="vulnerability-info">
            <h3>Vulnerability Info:</h3>
            <p>This upload form is vulnerable to:</p>
            <ul>
                <li>Path traversal attacks</li>
                <li>Unrestricted file upload</li>
                <li>No file type validation</li>
            </ul>
            <p>Try uploading: ../../../malicious.php</p>
        </div>
        
        <a href="orders.php">Back to Orders</a>
    </div>
</body>
</html>