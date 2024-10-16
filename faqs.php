<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQs - NW Complaint Management System</title>
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
            <h2 class="text-center mb-5"><i class="fas fa-question-circle"></i> Frequently Asked Questions</h2>
            <div class="row">
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-file-alt card-icon"></i>
                            <h3>Submitting a Complaint</h3>
                            <p>Learn how to submit your complaint effectively through our system.</p>
                            <a href="#" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#submitComplaintModal">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-search card-icon"></i>
                            <h3>Tracking Your Complaint</h3>
                            <p>Find out how to check the status of your submitted complaint.</p>
                            <a href="#" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#trackComplaintModal">Read More</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card text-center">
                        <div class="card-body">
                            <i class="fas fa-clock card-icon"></i>
                            <h3>Processing Time</h3>
                            <p>Understand the typical timeline for complaint resolution.</p>
                            <a href="#" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#processingTimeModal">Read More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="submitComplaintModal" tabindex="-1" aria-labelledby="submitComplaintModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitComplaintModalLabel">Submitting a Complaint</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>To submit a complaint:</p>
                    <ol>
                        <li>Navigate to the home page</li>
                        <li>Select the appropriate complaint type (Provincial, District, or Municipal)</li>
                        <li>Fill out the form with your details and complaint information</li>
                        <li>Review your information and submit the complaint</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="trackComplaintModal" tabindex="-1" aria-labelledby="trackComplaintModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="trackComplaintModalLabel">Tracking Your Complaint</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>To track your complaint:</p>
                    <ol>
                        <li>Go to the "Check Complaint Status" section on the home page</li>
                        <li>Enter your unique reference number</li>
                        <li>View the current status and details of your complaint</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="processingTimeModal" tabindex="-1" aria-labelledby="processingTimeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="processingTimeModalLabel">Processing Time</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>The typical timeline for complaint resolution is:</p>
                    <ul>
                        <li>Acknowledgement: Within 24 hours</li>
                        <li>Initial assessment: 1-2 business days</li>
                        <li>Investigation: 3-5 business days</li>
                        <li>Resolution: 5-10 business days</li>
                    </ul>
                    <p>Complex cases may require additional time. You can always check the status of your complaint using your reference number.</p>
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