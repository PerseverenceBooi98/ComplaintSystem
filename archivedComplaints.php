<?php
session_start();
require_once 'config.php';

// Check if the user is logged in and is a provincial admin
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_type'] !== 'provincial') {
    header("Location: adminLogin.php");
    exit();
}

// Fetch department name
$department_id = $_SESSION['department_id'];
$department_query = $conn->prepare("SELECT department_name FROM departments WHERE department_id = ?");
$department_query->bind_param("i", $department_id);
$department_query->execute();
$department_result = $department_query->get_result();
$department = $department_result->fetch_assoc()['department_name'];

// Fetch archived complaints for the department
$archived_complaints_query = $conn->prepare("SELECT * FROM complaints WHERE department_id = ? AND status = 'Resolved'");
$archived_complaints_query->bind_param("i", $department_id);
$archived_complaints_query->execute();
$archived_complaints_result = $archived_complaints_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archived Complaints</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            height: 100vh;
            margin: 0;
        }
        .sidebar {
            min-width: 200px;
            background-color: #343a40; /* Dark grey */
            color: white;
            padding: 15px;
            border-right: 1px solid #dee2e6;
        }
        .sidebar h4 {
            color: #ffffff;
        }
        .sidebar a {
            color: #ffffff;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #495057; /* Darker grey on hover */
            padding: 10px;
            border-radius: 5px;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background-color: #f8f9fa; /* Light background for content */
        }
        .table thead th {
            background-color: #28a745; /* Green header */
            color: white;
        }
        .table {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Navigation</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="provincialAdminDashboard.php">Complaints</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contacts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="archivedComplaints.php">Archived Complaints</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
    <div class="content">
        <h1>Archived Complaints for <?php echo $department; ?></h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reference Number</th>
                    <th>Complainant Name</th>
                    <th>Complaint Details</th>
                    <th>Status</th>
                    <th>Resolved At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($complaint = $archived_complaints_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $complaint['id']; ?></td>
                        <td><?php echo $complaint['reference_number']; ?></td>
                        <td><?php echo $complaint['complainant_name'] . ' ' . $complaint['complainant_surname']; ?></td>
                        <td><?php echo $complaint['description']; ?></td>
                        <td><?php echo $complaint['status']; ?></td>
                        <td><?php echo $complaint['updated_at']; ?></td> <!-- Assuming updated_at is when it was resolved -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>