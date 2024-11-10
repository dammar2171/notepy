<?php
$conn = new mysqli('localhost', 'root', '', 'test_db');

// Get the file ID from the URL
$id = $_GET['id'];

// Fetch the file's path from the database before deletion
$sql = "SELECT filepath FROM files WHERE id=$id";
$result = $conn->query($sql);
$file = $result->fetch_assoc();

// Delete the file from the database
$sql = "DELETE FROM files WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    // Optionally, delete the file from the server
    if (file_exists($file['filepath'])) {
        unlink($file['filepath']);  // Delete the file from the server
    }
    echo "<script>alert('File deleted successfully!'); window.location.href='index.php';</script>";
} else {
    echo "Error: " . $conn->error;
}
?>
