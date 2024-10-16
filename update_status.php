<?php
session_start();
require_once 'config.php';

// Check if the user is logged in and is a provincial admin
if (!isset($_SESSION['admin_type']) || $_SESSION['admin_type'] !== 'provincial') {
    header("Location: provincialAdminLogin.php");
    exit();
}

// Fetch complaint details if the request is GET
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $complaint_id = $_GET['id'];
    $sql = "SELECT * FROM complaints WHERE id = ?"; // Use 'id' instead of 'complaint_id'
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $complaint_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $complaint = $result->fetch_assoc();
    $stmt->close();
}

// Handle the POST request to update the status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complaint_id = $_POST['complaint_id'];
    $new_status = $_POST['status'];
    
    $sql = "UPDATE complaints SET status = ? WHERE id = ?"; // Use 'id' instead of 'complaint_id'
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $complaint_id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Status updated successfully!";
    } else {
        $_SESSION['message'] = "Error updating status: " . $conn->error;
    }
    
    $stmt->close();
    header("Location: provincialAdminDashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Complaint Status</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f0f0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #003366;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            border: none;
        }
        .btn:hover {
            background-color: #004c99;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Complaint Status</h1>
        <form action="update_status.php" method="POST">
            <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>"> <!-- Use 'id' -->
            <div class="form-group">
                <label for="status">New Status:</label>
                <select id="status" name="status" required>
                    <option value="Pending" <?php if($complaint['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                    <option value="In Progress" <?php if($complaint['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                    <option value="Resolved" <?php if($complaint['status'] == 'Resolved') echo 'selected'; ?>>Resolved</option>
                    <option value="Closed" <?php if($complaint['status'] == 'Closed') echo 'selected'; ?>>Closed</option>
                </select>
            </div>
            <button type="submit" class="btn">Update Status</button>
        </form>
    </div>
</body>
</html>

<?php
$conn->close();
?>