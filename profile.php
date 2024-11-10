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

// Fetch user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle Profile Picture Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_picture'])) {
    // Handle file upload
    $target_dir = "uploads/profile_pics/"; // Ensure this directory exists and is writable
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a real image or fake image
    $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check file size (optional, here it's 500KB max)
    if ($_FILES["profile_picture"]["size"] > 500000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // If everything is ok, try to upload file
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Update database with the new profile picture path
            $sql = "UPDATE users SET profile_picture=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $target_file, $user_id);
            if ($stmt->execute()) {
                echo "<script>alert('Profile picture updated successfully!'); window.location.href='profile.php';</script>";
            } else {
                echo "Error updating profile picture: " . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}

// Update Profile Information
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !isset($_FILES['profile_picture'])) {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    $sql = "UPDATE users SET full_name=?, email=?, phone_number=?, address=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $full_name, $email, $phone_number, $address, $user_id);
    
    if ($stmt->execute()) {
        echo "<script>alert('Profile updated successfully!'); window.location.href='profile.php';</script>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* General styles */
body {
    background-color: #f0f2f5;
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
}

nav {
    background-color: #007bff; /* Primary color */
    padding: 15px;
    text-align: center;
}

nav a {
    color: white;
    text-decoration: none;
    padding: 10px 15px;
    margin: 0 10px;
    border-radius: 5px;
    transition: background-color 0.3s;
}

nav a:hover {
    background-color: #0056b3;
}

/* Container for the profile */
.container {
    max-width: 500px;
    margin: 10px auto;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
}

/* Header styles */
h2 {
    text-align: center;
    color: #1877f2;
}

/* Form styles */
form {
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    color: #333;
}

input[type="text"],
input[type="email"],
input[type="file"] {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    margin-top: 5px;
    margin-bottom: 15px;
}

input[type="file"] {
    padding: 3px;
}

/* Button styles */
button {
    background-color: #1877f2;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    width: 100%;
    font-size: 16px;
}

button:hover {
    background-color: #0056b3;
}

/* Profile picture styles */
img {
    border-radius: 50%;
    margin-bottom: 10px;
    border: 2px solid #ddd;
}

/* Logout link styles */
p {
    text-align: center;
}

p a {
    color: #1877f2;
    text-decoration: none;
    font-weight: bold;
}

p a:hover {
    text-decoration: underline;
}

    </style>
</head>
<body>
<nav>
    <a href="user.php">Home</a>
    <a href="profile.php">My Profile</a>
    <a href="logout.php">Logout</a>
</nav>
    <div class="container">
        <h2>User Profile</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <label for="profile_picture">Profile Picture:</label><br>
            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" width="100" height="100"><br>
            <input type="file" name="profile_picture" required><br><br>
            <button type="submit">Update Profile Picture</button>
        </form>

        <form method="POST" action="">
            <label for="full_name">Full Name:</label><br>
            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required><br><br>
            
            <label for="email">Email:</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>
            
            <label for="phone_number">Phone Number:</label><br>
            <input type="text" name="phone_number" value="<?php echo htmlspecialchars($user['phone_number']); ?>"><br><br>

            <label for="address">Address:</label><br>
            <input type="text" name="address" value="<?php echo htmlspecialchars($user['address']); ?>"><br><br>

            <button type="submit">Update Profile</button>
        </form>
        <p><a href="change_password.php">Change Password</a></p>
        <p><a href="logout.php">Logout</a></p>
    </div>
</body>
</html>
