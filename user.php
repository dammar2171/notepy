<?php
session_start(); // Start the session

// Database connection
$conn = new mysqli('localhost', 'root', '', 'test_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login page if not logged in
    exit(); // Stop further execution
}

// Initialize the search term
$searchTerm = '';
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
}

// Modify the SQL query based on the search term
$sql = "SELECT * FROM files WHERE filename LIKE '%$searchTerm%'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User File List</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        nav {
    background-color: #007bff; /* Primary color */
    padding: 15px; /* Padding around the links */
    text-align: center; /* Center the links */
}

nav a {
    color: white; /* Text color */
    text-decoration: none; /* Remove underline */
    padding: 10px 15px; /* Padding for links */
    margin: 0 10px; /* Space between links */
    border-radius: 5px; /* Rounded corners */
    transition: background-color 0.3s; /* Smooth transition */
}

nav a:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

        /* Search Form Style */
        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        form input[type="text"] {
            width: 300px; /* Set width for the search input */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px; /* Rounded corners for left side */
            font-size: 16px; /* Font size */
        }

        form button {
            padding: 10px 15px;
            border: none;
            background-color: #007bff; /* Primary color */
            color: white;
            font-size: 16px; /* Font size */
            border-radius: 0 5px 5px 0; /* Rounded corners for right side */
            cursor: pointer; /* Pointer cursor on hover */
        }

        form button:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .card {
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            margin: 10px;
            padding: 15px;
            width: 250px;
            text-align: center;
        }
        .card h3 {
            font-size: 18px;
            margin: 10px 0;
        }
        .card a {
            display: inline-block;
            margin: 5px;
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 5px 10px;
            border-radius: 3px;
        }
        .card a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<nav>
    <a href="user.php">Home</a>
    <a href="profile.php">My Profile</a> <!-- Link to profile page -->
    <a href="logout.php">Logout</a>
</nav>


<h2>Available Files</h2>

<!-- Logout Link -->
<p style="text-align: right;">
    <a href="logout.php" style="background-color: #007bff; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">Logout</a>
</p>

<!-- Search Form -->
<form method="POST">
    <input type="text" name="search" placeholder="Search files..." value="<?php echo htmlspecialchars($searchTerm); ?>">
    <button type="submit">Search</button>
</form>

<div class="container">
    <?php while($row = $result->fetch_assoc()): ?>
    <div class="card">
        <h3><?php echo htmlspecialchars($row['filename']); ?></h3>
        <p>Type: <?php echo htmlspecialchars($row['filetype']); ?></p>
        <a href="<?php echo htmlspecialchars($row['filepath']); ?>" target="_blank" download>Download</a>
        <a href="<?php echo htmlspecialchars($row['filepath']); ?>" target="_blank">View</a>
    </div>
    <?php endwhile; ?>
</div>

</body>
</html>
