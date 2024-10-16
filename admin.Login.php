<?php
session_start();
require_once 'config.php';

// Check if the user is already logged in
if (isset($_SESSION['admin_id'])) {
    // Redirect to the appropriate dashboard based on admin type
    switch ($_SESSION['admin_type']) {
        case 'provincial':
            header("Location: provincialAdminDashboard.php");
            exit();
        case 'district':
            header("Location: districtAdminDashboard.php");
            exit();
        case 'municipal':
            header("Location: municipalAdminDashboard.php");
            exit();
        case 'division':
            header("Location: divisionAdminDashboard.php");
            exit();
    }
}

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Query to select the admin from the database
    $stmt = $conn->prepare("SELECT id, admin_type, password, department_id, division_id, municipality_id FROM admins WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    // Verify password (plain text comparison)
    if ($admin && $password === $admin['password']) { // Compare plain text passwords
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_type'] = $admin['admin_type'];
        $_SESSION['department_id'] = $admin['department_id']; // Store department ID
        $_SESSION['division_id'] = $admin['division_id']; // Store division ID if applicable
        $_SESSION['municipality_id'] = $admin['municipality_id']; // Store municipality ID

        // Redirect based on admin type
        switch ($admin['admin_type']) {
            case 'provincial':
                header("Location: provincialAdminDashboard.php");
                break;
            case 'district':
                header("Location: districtAdminDashboard.php");
                break;
            case 'municipal':
                header("Location: municipalAdminDashboard.php");
                break;
            case 'division':
                header("Location: divisionAdminDashboard.php");
                break;
        }
        exit(); // Ensure no further code is executed after redirection
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"> <!-- Font Awesome -->
    <style>
        body {
            background-image: url('images/Background.jpg'); /* Set the background image */
            background-size: cover; /* Cover the entire viewport */
            background-position: center; /* Center the image */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9); /* White background with transparency */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 400px; /* Fixed width for the form */
            z-index: 2; /* Ensure the form is above the background */
        }
        .login-container img {
            max-width: 100%; /* Responsive logo */
            height: auto;
            margin-bottom: 20px; /* Space below the logo */
        }
        .login-container h2 {
            margin-bottom: 20px; /* Space below the heading */
            color: #343a40; /* Dark text color */
        }
        .btn-primary {
            background-color: #007bff; /* Primary button color */
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Darker shade on hover */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="images/provincial-logo.png" alt="Provincial Logo">
        <h2>Admin Login</h2>
        <?php if (isset($error)) { echo "<p class='alert alert-danger'>$error</p>"; } ?>
        <form method="POST" action="">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
</body>
</html>