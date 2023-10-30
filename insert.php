<?php
// Check if a file was uploaded
if (isset($_FILES['file'])) {
    $uploadDirectory = 'uploads/'; // Specify the directory where you want to save the uploaded files

    // Check if the target directory exists, create it if not
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    // Get the uploaded file's information
    $file = $_FILES['file'];

    // Get the file name and path on the server
    $fileName = basename($file['name']);
    $uploadPath = $uploadDirectory . $fileName;

    // Move the uploaded file to the target directory
    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
        echo 'File uploaded successfully.';
    } else {
        echo 'File upload failed.';
    }
} else {
    echo 'No file uploaded.';
}
?>
