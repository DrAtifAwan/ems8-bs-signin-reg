<?php 
session_start();
require_once 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        if ($role === 'organizer') {
            $sql = "SELECT * FROM organizers WHERE email = ?";
        } elseif ($role === 'attendee') {
            $sql = "SELECT * FROM attendees WHERE email = ?";
        } else {
            echo "<script>alert('Invalid role selected');</script>";
            exit();
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['role'] = $role;
                if ($role === 'organizer') {
                    header("Location: organizer_dashboard.php");
                } elseif ($role === 'attendee') {
                    header("Location: attendee_dashboard.php");
                }
                exit();
            } else {
                echo "<script>alert('Incorrect password');</script>";
            }
        } else {
            echo "<script>alert('No user found with this email');</script>";
        }
        $stmt->close();
    } elseif ($_POST['action'] === 'register') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = $_POST['role'];

        if ($role === 'organizer') {
            $sql = "INSERT INTO organizers (name, email, password) VALUES (?, ?, ?)";
        } elseif ($role === 'attendee') {
            $sql = "INSERT INTO attendees (name, email, password) VALUES (?, ?, ?)";
        } else {
            echo "<script>alert('Invalid role selected');</script>";
            exit();
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            echo "<script>alert('Registration successful!');</script>";
        } else {
            echo "<script>alert('Registration failed. Please try again.');</script>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Registration</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleForms() {
            document.getElementById('loginForm').classList.toggle('d-none');
            document.getElementById('registrationForm').classList.toggle('d-none');
        }
    </script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <!-- Login Form -->
                <div id="loginForm" class="card p-4 shadow-sm bg-white">
                    <h2 class="text-center">Login</h2>
                    <form method="POST">
                        <input type="hidden" name="action" value="login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="organizer">Organizer</option>
                                <option value="attendee">Attendee</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="javascript:void(0)" onclick="toggleForms()">Don't have an account? Register</a>
                    </div>
                </div>

                <!-- Registration Form -->
                <div id="registrationForm" class="card p-4 shadow-sm bg-white d-none">
                    <h2 class="text-center">Register</h2>
                    <form method="POST">
                        <input type="hidden" name="action" value="register">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select name="role" id="role" class="form-select" required>
                                <option value="">Select Role</option>
                                <option value="organizer">Organizer</option>
                                <option value="attendee">Attendee</option>
                            </select>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Register</button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="javascript:void(0)" onclick="toggleForms()">Already have an account? Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
