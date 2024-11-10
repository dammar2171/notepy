<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'test_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];

    // Use prepared statements for security
    $sql = "UPDATE users SET full_name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $full_name, $email, $user_id);

    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "<script>alert('Error updating profile: " . $stmt->error . "');</script>";
    }

    $stmt->close(); // Close the statement
}
?>
