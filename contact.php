<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - NW Complaint Management System</title>
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
        .nav-tabs {
            border-bottom: 2px solid #28a745; /* Green border for tabs */
        }
        .nav-tabs .nav-link {
            border: none;
            border-radius: 0;
            color: #28a745;
            font-weight: bold;
            transition: background-color 0.3s, color 0.3s;
        }
        .nav-tabs .nav-link.active {
            background-color: #28a745; /* Active tab background */
            color: white; /* Active tab text color */
        }
        .nav-tabs .nav-link:hover {
            background-color: rgba(40, 167, 69, 0.1); /* Light green on hover */
            color: #28a745; /* Hover text color */
        }
        .tab-content {
            padding: 20px;
            border: 1px solid #28a745; /* Green border for content */
            border-radius: 10px;
            background-color: #f8f9fa; /* Light background for content */
            transition: opacity 0.3s ease;
        }
        .tab-pane {
            opacity: 0;
            transition: opacity 0.5s ease;
        }
        .tab-pane.show {
            opacity: 1; /* Fade in effect */
        }
        .contact-info {
            transition: transform 0.3s ease;
        }
        .contact-info:hover {
            transform: scale(1.05); /* Slightly enlarge on hover */
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
            <h2 class="text-center mb-5"><i class="fas fa-envelope"></i> Contact Us</h2>
            <!-- Horizontal Tabs for Departments -->
            <ul class="nav nav-tabs justify-content-center mb-4" id="departmentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="education-tab" data-bs-toggle="tab" href="#education" role="tab" aria-controls="education" aria-selected="true">Department of Education</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="health-tab" data-bs-toggle="tab" href="#health" role="tab" aria-controls="health" aria-selected="false">Department of Health</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="social-dev-tab" data-bs-toggle="tab" href="#social-dev" role="tab" aria-controls="social-dev" aria-selected="false">Department of Social Development</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="agriculture-tab" data-bs-toggle="tab" href="#agriculture" role="tab" aria-controls="agriculture" aria-selected="false">Department of Agriculture and Rural Development</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="economic-dev-tab" data-bs-toggle="tab" href="#economic-dev" role="tab" aria-controls="economic-dev" aria-selected="false">Department of Economic Development and Tourism</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="public-works-tab" data-bs-toggle="tab" href="#public-works" role="tab" aria-controls="public-works" aria-selected="false">Department of Public Works and Roads</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="provincial-treasury-tab" data-bs-toggle="tab" href="#provincial-treasury" role="tab" aria-controls="provincial-treasury" aria-selected="false">Department of Provincial Treasury</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="human-settlements-tab" data-bs-toggle="tab" href="#human-settlements" role="tab" aria-controls="human-settlements" aria-selected="false">Department of Human Settlements</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="office-premier-tab" data-bs-toggle="tab" href="#office-premier" role="tab" aria-controls="office-premier" aria-selected="false">Office of the Premier</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="arts-culture-tab" data-bs-toggle="tab" href="#arts-culture" role="tab" aria-controls="arts-culture" aria-selected="false">Department of Arts and Culture, Sports and Recreation</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="sassa-tab" data-bs-toggle="tab" href="#sassa" role="tab" aria-controls="sassa" aria-selected="false">SASSA</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="home-affairs-tab" data-bs-toggle="tab" href="#home-affairs" role="tab" aria-controls="home-affairs" aria-selected="false">Home Affairs</a>
                </li>
            </ul>

            <div class="tab-content" id="departmentTabsContent">
                <div class="tab-pane fade show active" id="education" role="tabpanel" aria-labelledby="education-tab">
                    <h4>Department of Education</h4>
                    <p class="contact-info">Phone: +27 12 456 7890<br>Email: education@nwcomplaint.gov.za</p>
                </div>
                <div class="tab-pane fade" id="health" role="tabpanel" aria-labelledby="health-tab">
                    <h4>Department of Health</h4>
                    <p class="contact-info">Phone: +27 12 987 6543<br>Email: health@nwcomplaint.gov.za</p>
                </div>
                <div class="tab-pane fade" id="social-dev" role="tabpanel" aria-labelledby="social-dev-tab">
                    <h4>Department of Social Development</h4>
                    <p class="contact-info">Phone: +27 12 321 6543<br>Email: socialdev@nwcomplaint.gov.za</p>
                </div>
                <div class="tab-pane fade" id="agriculture" role="tabpanel" aria-labelledby="agriculture-tab">
                    <h4>Department of Agriculture and Rural Development</h4>
                    <p class="contact-info">Phone: +27 12 345 6789<br>Email: agriculture@nwcomplaint.gov.za</p>
                </div>
                <div class="tab-pane fade" id="economic-dev" role="tabpanel" aria-labelledby="economic-dev-tab">
                    <h4>Department of Economic Development and Tourism</h4>
                    <p class="contact-info">Phone: +27 12 654 3210<br>Email: economicdev@nwcomplaint.gov.za</p>
                </div>
                <div class="tab-pane fade" id="public-works" role="tabpanel" aria-labelledby="public-works-tab">
                    <h4>Department of Public Works and Roads</h4>
                    <p class="contact-info">Phone: +27 12 789 0123<br>Email: publicworks@nwcomplaint.gov.za</p>
                </div>
                <div class="tab-pane fade" id="provincial-treasury" role="tabpanel" aria-labelledby="provincial-treasury-tab">
                    <h4>Department of Provincial Treasury</h4>
                    <p class="contact-info">Phone: +27 12 345 6789<br>Email: treasury@nwcomplaint.gov.za</p>
                </div>
                <div class="tab-pane fade" id="human-settlements" role="tabpanel" aria-labelledby="human-settlements-tab">
                    <h4>Department of Human Settlements</h4>
                    <p class="contact-info">Phone: +27 12 456 7890<br>Email: humansettlements@nwcomplaint.gov.za</p>
                </div>
                <div class="tab-pane fade" id="office-premier" role="tabpanel" aria-labelledby="office-premier-tab">
                    <h4>Office of the Premier</h4>
                    <p class="contact-info">Phone: +27 12 345 6789<br>Email: premier@nwcomplaint.gov.za</p>
                </div>
                <div class="tab-pane fade" id="arts-culture" role="tabpanel" aria-labelledby="arts-culture-tab">
                    <h4>Department of Arts and Culture, Sports and Recreation</h4>
                    <p class="contact-info">Phone: +27 12 321 6543<br>Email: artsculture@nwcomplaint.gov.za</p>
                </div>
                <div class="tab-pane fade" id="sassa" role="tabpanel" aria-labelledby="sassa-tab">
                    <h4>SASSA</h4>
                    <p class="contact-info">Phone: +27 12 987 6543<br>Email: sassa@nwcomplaint.gov.za</p>
                </div>
                <div class="tab-pane fade" id="home-affairs" role="tabpanel" aria-labelledby="home-affairs-tab">
                    <h4>Home Affairs</h4>
                    <p class="contact-info">Phone: +27 12 123 4567<br>Email: homeaffairs@nwcomplaint.gov.za</p>
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