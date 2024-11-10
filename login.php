<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'test_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user data from the database
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();

    // Verify the password
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id']; // Set session variable
        header('Location: user.php'); // Redirect to user page
        exit();
    } else {
        echo "<script>alert('Invalid username or password!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f0f0f0;
    margin: 0;
    padding: 10px;
}

.container {
    max-width: 400px; /* Set a maximum width for the container */
    margin: auto; /* Center the container */
    background-color: white;
    border-radius: 8px; /* Rounded corners */
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    padding: 5px;
}

h2 {
    text-align: center;
    color: #333; /* Darker text color */
}

form {
    display: flex;
    flex-direction: column; /* Stack form elements vertically */
    gap: 15px; /* Space between form elements */
}

label {
    font-weight: bold; /* Make labels bold */
}

input[type="text"],
input[type="password"] {
    padding: 10px; /* Add padding for input fields */
    border: 1px solid #ccc; /* Light gray border */
    border-radius: 5px; /* Rounded corners */
    font-size: 16px; /* Font size */
}

button {
    padding: 10px; /* Button padding */
    border: none;
    border-radius: 5px; /* Rounded corners */
    background-color: #007bff; /* Primary color */
    color: white; /* Text color */
    font-size: 16px; /* Font size */
    cursor: pointer; /* Pointer cursor on hover */
    transition: background-color 0.3s; /* Smooth background color transition */
}

button:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

p {
    text-align: center; /* Center-align the paragraph */
}

a {
    color: #007bff; /* Link color */
    text-decoration: none; /* Remove underline */
}

a:hover {
    text-decoration: underline; /* Underline on hover */
}

    </style>
</head>
<body>
    <div class="container">
        <h2>User Login</h2>
        <form method="POST">
            <label for="username">Username:</label><br>
            <input type="text" name="username" required><br><br>
            <label for="password">Password:</label><br>
            <input type="password" name="password" required><br><br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
</body>
</html>
