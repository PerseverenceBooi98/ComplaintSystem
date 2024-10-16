<?php
session_start();
require_once 'config.php';

// Check if the user is logged in and is a division admin
if (!isset($_SESSION['admin_id']) || $_SESSION['admin_type'] !== 'division') {
    header("Location: admin.Login.php");
    exit();
}

// Fetch division ID from session
$division_id = $_SESSION['division_id'];

// Fetch division name
$division_query = $conn->prepare("SELECT division_name FROM divisions WHERE division_id = ?");
$division_query->bind_param("i", $division_id);
$division_query->execute();
$division_result = $division_query->get_result();
$division = $division_result->fetch_assoc();

// Debugging: Check if division was found
if (!$division) {
    die("Division not found for ID: " . htmlspecialchars($division_id));
}

// Fetch complaints for the division (only unresolved)
$complaints_query = $conn->prepare("SELECT c.* FROM complaints c WHERE c.division_id = ? AND c.status != 'Resolved'");
$complaints_query->bind_param("i", $division_id);
$complaints_query->execute();
$complaints_result = $complaints_query->get_result();

// Fetch statistics
$stats_query = $conn->prepare("SELECT COUNT(*) as total, SUM(status = 'Resolved') as resolved FROM complaints WHERE division_id = ?");
$stats_query->bind_param("i", $division_id);
$stats_query->execute();
$stats_result = $stats_query->get_result()->fetch_assoc();
$total_complaints = $stats_result['total'];
$resolved_complaints = $stats_result['resolved'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Division Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Add your custom styles here */
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
        .chart-container {
            margin-top: 20px;
            max-width: 400px; /* Set a max width for the chart */
            margin-left: auto; /* Center the chart */
            margin-right: auto; /* Center the chart */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h4>Navigation</h4>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="#">Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#">Contacts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="archivedComplaints.php">Archived Complaints</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
    <div class="content">
        <h1>Hello Admin of <?php echo htmlspecialchars($division['division_name']); ?></h1>
        <h2>Complaints Submitted to Your Division</h2>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reference Number</th>
                    <th>Complainant Name</th>
                    <th>Complainant Surname</th>
                    <th>Complainant Email</th>
                    <th>Complainant Phone</th>
                    <th>Location</th>
                    <th>Complaint Details</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($complaint = $complaints_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $complaint['id']; ?></td>
                        <td><?php echo $complaint['reference_number']; ?></td>
                        <td><?php echo $complaint['complainant_name']; ?></td>
                        <td><?php echo $complaint['complainant_surname']; ?></td>
                        <td><?php echo $complaint['complainant_email']; ?></td>
                        <td><?php echo $complaint['complainant_phone']; ?></td>
                        <td><?php echo $complaint['street_name'] . ', ' . $complaint['township'] . ', ' . $complaint['city'] . ', ' . $complaint['postal_code']; ?></td>
                        <td><?php echo $complaint['description']; ?></td>
                        <td><?php echo $complaint['status']; ?></td>
                        <td>
                            <form method="POST" action="update_status.php" style="display:inline;">
                                <input type="hidden" name="complaint_id" value="<?php echo $complaint['id']; ?>">
                                <select name="status" required>
                                    <option value="">Update Status</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="Resolved">Resolved</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-success">Update</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <div class="chart-container">
            <canvas id="complaintStatsChart"></canvas>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('complaintStatsChart').getContext('2d');
        const complaintStatsChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Total Complaints', 'Resolved Complaints'],
                datasets: [{
                    label: 'Complaints Statistics',
                    data: [<?php echo $total_complaints; ?>, <?php echo $resolved_complaints; ?>],
                    backgroundColor: [
                        'rgba(40, 167, 69, 0.5)', // Green for total complaints
                        'rgba(255, 193, 7, 0.5)'  // Yellow for resolved complaints
                    ],
                    borderColor: [
                        'rgba(40, 167, 69, 1)',
                        'rgba(255, 193, 7, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
