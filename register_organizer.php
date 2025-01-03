<?php
// Include the database connection file
include_once 'db_connect.php';

// Initialize session to manage user state
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Secure password hashing

    // Insert organizer into the database
    $query = "INSERT INTO organizers (name, email, password) VALUES ('$name', '$email', '$password')";
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Organizer registration successful!');</script>";
        header('Location: login.php');
        exit();
    } else {
        echo "<script>alert('Error registering organizer.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizer Registration</title>
   
</head>
<body>
    <div class="container">
        <h1>Organizer Registration</h1>
        <form method="POST" action="">
            <input type="text" name="name" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p style="text-align:center;">Already registered? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>
