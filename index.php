<?php
$conn = new mysqli('localhost', 'root', '', 'test_db');

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all files from the database
$sql = "SELECT * FROM files";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload CRUD Application</title>
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
        .card p {
            font-size: 14px;
            color: #555;
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

    .upload-link {
    text-align: center; /* Center the link */
    margin: 20px 0; /* Spacing above and below the link */
}

.upload-link a {
    background-color: #28a745; /* Green background for the link */
    color: white; /* White text color */
    padding: 10px 20px; /* Padding for the link */
    text-decoration: none; /* Remove underline */
    border-radius: 5px; /* Rounded corners */
    font-size: 18px; /* Font size for the link */
    transition: background-color 0.3s ease; /* Smooth transition for hover effect */
}

.upload-link a:hover {
    background-color: #218838; /* Darker green on hover */
}

    </style>
</head>
<body>

<h2>Uploaded Files</h2>
<div class="upload-link">
    <a href="create.php">Upload New File</a>
</div>
<div class="container">
    <?php while($row = $result->fetch_assoc()): ?>
    <div class="card">
        <h3><?php echo $row['filename']; ?></h3>
        <p>Type: <?php echo $row['filetype']; ?></p>
        <p>Description: <?php echo $row['description']; ?></p>
        <div>
            <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
            <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
            <a href="<?php echo $row['filepath']; ?>" target="_blank">View</a>
        </div>
    </div>
    <?php endwhile; ?>
</div>

</body>
</html>
