<?php
$conn = new mysqli('localhost', 'root', '', 'test_db');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filename = $_FILES['file']['name'];
    $filetype = $_FILES['file']['type'];
    $filepath = 'uploads/' . basename($filename);  // Save to uploads folder
    $description = $_POST['description']; // Get the description

    // Move uploaded file to the server
    if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {
        // Insert file data into the database
        $sql = "INSERT INTO files (filename, filetype, filepath, description) VALUES ('$filename', '$filetype', '$filepath', '$description')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<script>alert('File uploaded successfully!'); window.location.href='index.php';</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "<script>alert('Failed to upload file.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload File</title>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 20px;
}

h2 {
    text-align: center;
    color: #333;
}

.form-container {
    background-color: white;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    max-width: 500px; /* Maximum width for the form container */
    margin: 0 auto; /* Centering the container */
    padding: 20px; /* Padding inside the container */
}

label {
    font-weight: bold; /* Bold for labels */
    display: block; /* Each label on a new line */
    margin: 10px 0 5px; /* Spacing around labels */
}

input[type="file"], 
textarea {
    width: 100%; /* Full width for inputs and text area */
    padding: 10px; /* Padding inside the input fields */
    border: 1px solid #ddd; /* Border style */
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Font size */
}

textarea {
    resize: none; /* Prevent resizing */
}

button {
    background-color: #007bff; /* Primary button color */
    color: white; /* Text color for button */
    padding: 10px 15px; /* Padding for the button */
    border: none; /* No border */
    border-radius: 5px; /* Rounded corners */
    cursor: pointer; /* Pointer cursor on hover */
    font-size: 16px; /* Font size */
    margin-top: 15px; /* Margin at the top of the button */
    width: 100%; /* Full width for button */
}

button:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

    </style>
</head>
<body>

<h2>Upload New File</h2>
<div class="form-container">
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="file">Select File:</label>
        <input type="file" name="file" required>

        <label for="description">File Description:</label>
        <textarea name="description" rows="4" required></textarea>

        <button type="submit">Upload</button>
    </form>
</div>

</body>
</html>
