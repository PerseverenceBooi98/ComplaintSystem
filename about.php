<?php
require_once 'config.php';  // This includes your database connection file
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - NW Complaint Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        .navbar { 
            background-color: #28a745 !important; 
        }
        .navbar-nav .nav-link { 
            color: white !important; 
        }
        .navbar-nav .nav-link:hover { 
            color: #f8f9fa !important; 
        }
        .section {
            padding: 60px 0;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }
        .card-icon {
            font-size: 3rem;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="publicDashboard.php"><img src="images/provincial-logo.png" alt="Logo" height="60"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="publicDashboard.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php"><i class="fas fa-info-circle"></i> About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="faqs.php"><i class="fas fa-question-circle"></i> FAQs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php"><i class="fas fa-envelope"></i> Contact</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-language"></i> Language
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                            <li><a class="dropdown-item" href="?lang=en">English</a></li>
                            <li><a class="dropdown-item" href="?lang=tn">Setswana</a></li>
                            <li><a class="dropdown-item" href="?lang=zu">Zulu</a></li>
                            <li><a class="dropdown-item" href="?lang=af">Afrikaans</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="section">
        <div class="container">
            <h2 class="text-center mb-5"><i class="fas fa-info-circle"></i> About NW Complaint Management System</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-bullseye card-icon"></i>
                            <h3>Our Mission</h3>
                            <p>To provide a user-friendly platform that empowers citizens to voice their concerns and enables government officials to address these issues effectively.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-cogs card-icon"></i>
                            <h3>How It Works</h3>
                            <ol class="text-start">
                                <li>Submit complaints online</li>
                                <li>Complaints are routed to departments</li>
                                <li>Officials review and take action</li>
                                <li>Track complaint status</li>
                                <li>Receive resolution notification</li>
                            </ol>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-chart-line card-icon"></i>
                            <h3>Our Impact</h3>
                            <div class="mt-4">
                                <?php
                                // Fetch the statistics from the database
                                $query = "SELECT 
                                            COUNT(*) as total_complaints,
                                            SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved_complaints,
                                            AVG(DATEDIFF(updated_at, created_at)) as avg_resolution_time
                                          FROM complaints";
                                $result = $conn->query($query);
                                if ($result) {
                                    $stats = $result->fetch_assoc();

                                    $total_complaints = $stats['total_complaints'];
                                    $resolved_complaints = $stats['resolved_complaints'];
                                    $resolution_rate = ($total_complaints > 0) ? ($resolved_complaints / $total_complaints) * 100 : 0;
                                    $avg_resolution_time = round($stats['avg_resolution_time'], 1);
                                ?>
                                    <h4 class="mb-3"><?php echo number_format($total_complaints); ?></h4>
                                    <p>Total Complaints</p>
                                    <h4 class="mb-3 mt-4"><?php echo number_format($resolved_complaints); ?></h4>
                                    <p>Resolved Complaints</p>
                                    <h4 class="mb-3 mt-4"><?php echo number_format($resolution_rate, 1); ?>%</h4>
                                    <p>Resolution Rate</p>
                                    <h4 class="mb-3 mt-4"><?php echo $avg_resolution_time; ?> Days</h4>
                                    <p>Average Resolution Time</p>
                                <?php
                                } else {
                                    echo "<p>Unable to fetch statistics at this time.</p>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        navLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                if (this.getAttribute('href') !== '#') {
                    e.preventDefault();
                    window.location.href = this.getAttribute('href');
                }
            });
        });
    });
    </script>
</body>
</html>

