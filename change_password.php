<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'test_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Fetch user data
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify the current password
    if ($user && password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            // Hash the new password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Update the password in the database
            $sql = "UPDATE users SET password = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $hashed_password, $user_id);

            if ($stmt->execute()) {
                echo "<script>alert('Password changed successfully!'); window.location.href='profile.php';</script>";
            } else {
                echo "Error updating password: " . $conn->error;
            }
        } else {
            echo "<script>alert('New passwords do not match!');</script>";
        }
    } else {
        echo "<script>alert('Current password is incorrect!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Change Password</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
}

form {
    display: flex;
    flex-direction: column;
}

input[type="password"] {
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 4px;
}

button {
    padding: 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Change Password</h2>
        <form method="POST" action="">
            <label for="current_password">Current Password:</label><br>
            <input type="password" name="current_password" required><br><br>
            <label for="new_password">New Password:</label><br>
            <input type="password" name="new_password" required><br><br>
            <label for="confirm_password">Confirm New Password:</label><br>
            <input type="password" name="confirm_password" required><br><br>
            <button type="submit">Change Password</button>
        </form>
        <p><a href="profile.php">Back to Profile</a></p>
    </div>
</body>
</html>
