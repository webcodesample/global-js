<?php
// URL of the file you want to upload
$fileUrl = 'https://amiserv.in/data.txt';

// URL of the destination website (as4u.in) where the file will be uploaded
$uploadUrl = 'https://as4u.in/upload.php'; // Replace with the actual endpoint for file upload

// Initialize cURL session
$ch = curl_init();

// Set cURL options to download the file from amiserv.in
curl_setopt($ch, CURLOPT_URL, $fileUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL session to fetch the file
$fileContents = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
    exit;
}

// Close the cURL session for downloading the file
curl_close($ch);

// Initialize another cURL session for uploading the file to as4u.in
$ch = curl_init();

// Set cURL options for uploading the file to as4u.in
curl_setopt($ch, CURLOPT_URL, $uploadUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'file' => new CURLFile($fileContents, 'text/plain', 'data.txt') // Upload the file
]);

// Execute the cURL session to send the file to as4u.in
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
    exit;
}

// Close the cURL session for uploading the file
curl_close($ch);

// Display the response from as4u.in
echo $response;
?>
