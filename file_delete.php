//from chat gpt
<?php
$fileToDelete = 'path/to/your/file.php';

if (file_exists($fileToDelete)) {
    if (unlink($fileToDelete)) {
        echo "File deleted successfully: " . $fileToDelete;
    } else {
        echo "Error deleting the file: " . $fileToDelete;
    }
} else {
    echo "File not found: " . $fileToDelete;
}
?>
<?php
$fileToDelete = 'path/to/your/file.txt';

if (file_exists($fileToDelete)) {
    if (unlink($fileToDelete)) {
        echo "File deleted successfully: " . $fileToDelete;
    } else {
        echo "Error deleting the file: " . $fileToDelete;
    }
} else {
    echo "File not found: " . $fileToDelete;
}
?>
//from stackoverflow
<?php
$files = [
    './first.jpg',
    './second.jpg',
    './third.jpg'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        unlink($file);
    } else {
        // File not found.
    }
}
?>
