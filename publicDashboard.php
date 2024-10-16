<?php
// Include the database connection
require_once 'config.php';

// Check if the connection is established
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$complaint_data = null;
$error_message = null;
$total_complaints = 0;
$resolved_complaints = 0;

if (isset($_GET['ref'])) {
    $ref = $_GET['ref'];
    $stmt = $conn->prepare("SELECT c.*, m.municipality_name, d.department_name, dis.district_name 
                            FROM complaints c
                            LEFT JOIN municipalities m ON c.municipality_id = m.municipality_id
                            LEFT JOIN departments d ON c.department_id = d.department_id
                            LEFT JOIN districts dis ON c.district_id = dis.district_id
                            WHERE c.reference_number = ?");
    $stmt->bind_param("s", $ref);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $complaint_data = $result->fetch_assoc();
    } else {
        $error_message = "No complaint found with the given reference number.";
    }
}


// Get total and resolved complaints count
$count_stmt = $conn->prepare("SELECT COUNT(*) as total, 
                              SUM(CASE WHEN status = 'Resolved' THEN 1 ELSE 0 END) as resolved 
                              FROM complaints");
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$count_data = $count_result->fetch_assoc();
$total_complaints = $count_data['total'];
$resolved_complaints = $count_data['resolved'];

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to fetch weather data for a specific city
function fetchWeather($city, $apiKey) {
    $url = "https://api.openweathermap.org/data/2.5/weather?q={$city},ZA&appid=" . $apiKey . "&units=metric";
    $response = @file_get_contents($url); // Suppress warnings with @
    
    if ($response === FALSE) {
        echo "Error fetching data for {$city}<br>"; // Debugging output
        return null; // Return null on failure
    }

    $data = json_decode($response, true);
    
    // Check if the API returned an error
    if (isset($data['cod']) && $data['cod'] !== 200) {
        echo "Error: " . htmlspecialchars($data['message']) . "<br>"; // Debugging output
        return null; // Return null if the API response indicates an error
    }

    return $data; // Return the decoded JSON data
}

// Your API key
$weatherApiKey = '6ca0d41c1578d36200a9f0f860a36f0b'; // Replace with your actual API key

// List of cities in North West Province
$cities = ['Mahikeng', 'Rustenburg', 'Potchefstroom', 'Klerksdorp', 'Lichtenburg', 'Brits', 'Vryburg', 'Zeerust'];
$weatherDataList = [];

// Fetch weather data for each city
foreach ($cities as $city) {
    $weatherData = fetchWeather($city, $weatherApiKey);
    if ($weatherData) {
        $weatherDataList[$city] = $weatherData;
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NW Complaint Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
        }
        .navbar {
            background-color: #28a745 !important;
            padding: 15px 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-light .navbar-nav .nav-link {
            color: white !important;
            transition: color 0.3s ease;
        }
        .navbar-light .navbar-nav .nav-link:hover {
            color: #f8f9fa !important;
        }
        .complaint-type {
            transition: all 0.3s;
            width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            margin: 10px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        .complaint-type:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }
        .complaint-type span {
            transition: all 0.3s;
        }
        .complaint-type i {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 2em;
            opacity: 0;
            transition: all 0.3s;
        }
        .complaint-type:hover span {
            opacity: 0;
        }
        .complaint-type:hover i {
            opacity: 1;
        }
        .section {
            padding: 50px 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .section-alt {
            background-color: rgba(248, 249, 250, 0.7);
            position: relative;
            overflow: hidden;
        }
        .section-alt::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('images/picture1.jpg'), url('images/picture2.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.4;
            animation: fadeBackground 20s infinite;
        }
        @keyframes fadeBackground {
            0%, 100% { background-image: url('images/picture1.jpg'); }
            50% { background-image: url('images/picture2.jpg'); }
        }
        .section-alt .container {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 8px;
        }
        .section-alt h3,
        .section-alt .form-control,
        .section-alt .btn-primary {
            position: relative;
            z-index: 2;
        }
        .section-alt .form-control {
            background-color: rgba(255, 255, 255, 0.9);
        }
        .section-alt .btn-primary {
            background-color: rgba(0, 123, 255, 0.9);
        }
        footer {
            background-color: #28a745;
            color: white;
            padding: 20px 0;
        }
        footer a {
            color: white;
        }
        .complaint-details {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            padding: 20px;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .complaint-details:hover {
            box-shadow: 0 6px 8px rgba(0,0,0,0.15);
        }
        .complaint-details table {
            width: 100%;
        }
        .complaint-details th {
            width: 30%;
            text-align: right;
            padding-right: 15px;
            color: #28a745;
        }
        .complaint-details td {
            width: 70%;
            padding-left: 15px;
        }
        .complaint-status {
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-pending {
            color: #ffc107;
        }
        .status-in-progress {
            color: #17a2b8;
        }
        .status-resolved {
            color: #28a745;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        /* Chatbox CSS */
        .chatbox {
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 350px; /* Increased width for better visibility */
            border: 2px solid #28a745; /* Thicker border for visibility */
            border-radius: 5px;
            display: none; /* Hidden by default */
            flex-direction: column;
            background-color: #ffffff; /* White background for contrast */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3); /* Stronger shadow */
            z-index: 1000; /* Ensure it is on top of other elements */
        }

        .chatbox-header {
            background-color: #28a745; /* Header color */
            color: white;
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold; /* Make header text bold */
        }

        .chatbox-body {
            padding: 10px;
            height: 300px; /* Increased height for better visibility */
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .message {
            display: flex;
            align-items: flex-start;
            margin-bottom: 10px;
        }

        .bot-message {
            align-self: flex-start;
        }

        .user-message {
            align-self: flex-end;
        }

        .profile-pic {
            width: 40px; /* Size of the profile picture */
            height: 40px;
            border-radius: 50%;
            margin-right: 10px; /* Space between image and text */
        }

        .bot-message span {
            background-color: #e9ecef; /* Light background for bot messages */
            border-radius: 15px;
            padding: 10px;
            max-width: 70%; /* Limit width of bot messages */
            word-wrap: break-word; /* Allow long words to break */
        }

        .user-message span {
            background-color: #28a745; /* Green background for user messages */
            color: white; /* White text for user messages */
            border-radius: 15px;
            padding: 10px;
            max-width: 70%; /* Limit width of user messages */
            word-wrap: break-word; /* Allow long words to break */
        }

        .chatbox-footer {
            display: flex;
            padding: 10px;
            background-color: #f8f9fa; /* Light background for footer */
            border-top: 1px solid #ccc; /* Add a border to separate from messages */
        }

        .chatbox-footer input {
            flex: 1;
            padding: 5px;
            border: 1px solid #28a745; /* Match input border with chatbox */
            border-radius: 5px; /* Rounded corners for input */
            outline: none; /* Remove default outline */
        }

        .chatbox-footer button {
            background-color: #28a745; /* Match button color with chatbox */
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            margin-left: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .chatbox-footer button:hover {
            background-color: #218838; /* Darker green on hover */
        }

        .open-chat-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 15px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s, transform 0.3s;
            z-index: 1000; /* Ensure it is on top of other elements */
        }

        .open-chat-btn:hover {
            background-color: #218838;
            transform: scale(1.1);
        }

        .weather-card {
            background-color: #f8f9fa; /* Light background for cards */
            border: 1px solid #dee2e6; /* Border color */
            border-radius: 10px; /* Rounded corners */
            transition: transform 0.3s; /* Animation on hover */
        }
        .weather-card:hover {
            transform: scale(1.05); /* Scale up on hover */
        }
        .weather-icon {
            width: 80px; /* Icon size */
            height: 80px; /* Icon size */
        }
        .fade {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }
        .fade.show {
            opacity: 1;
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
            <h2 class="text-center mb-4"><i class="fas fa-bullhorn mr-2"></i> Welcome to NW Complaint Management System</h2>
            <p class="text-center mb-5">Submit your complaint or check the status of an existing complaint.</p>
            <h3 class="text-center mb-4">Submit a Complaint</h3>
            <div class="d-flex justify-content-center">
                <a href="forms/provincialForm.php" class="btn btn-primary btn-lg complaint-type">
                    <span>Provincial</span>
                    <i class="fas fa-landmark"></i>
                </a>
                <a href="forms/districtForm.php" class="btn btn-primary btn-lg complaint-type">
                    <span>District</span>
                    <i class="fas fa-map-marked-alt"></i>
                </a>
                <a href="forms/municipalForm.php" class="btn btn-primary btn-lg complaint-type">
                    <span>Municipal</span>
                    <i class="fas fa-city"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="section section-alt">
        <div class="container">
            <h3 class="text-center mb-4"><i class="fas fa-search mr-2"></i> Check Complaint Status</h3>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <form action="" method="GET">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" placeholder="Enter complaint reference" name="ref">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </form>
                    <?php if ($error_message): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php elseif ($complaint_data): ?>
                        <div class="complaint-details">
                            <h4 class="mb-4">Complaint Details</h4>
                            <table class="table table-borderless">
                                <tr>
                                    <th>Reference Number:</th>
                                    <td><?php echo htmlspecialchars($complaint_data['reference_number']); ?></td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        <span class="complaint-status status-<?php echo strtolower(str_replace(' ', '-', $complaint_data['status'])); ?>">
                                            <?php echo htmlspecialchars($complaint_data['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Complainant:</th>
                                    <td><?php echo htmlspecialchars($complaint_data['complainant_name'] . ' ' . $complaint_data['complainant_surname']); ?></td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td><?php echo htmlspecialchars($complaint_data['complainant_email']); ?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><?php echo htmlspecialchars($complaint_data['complainant_phone']); ?></td>
                                </tr>
                                <tr>
                                    <th>Municipality:</th>
                                    <td><?php echo htmlspecialchars($complaint_data['municipality_name'] ?? 'Not specified'); ?></td>
                                </tr>
                                <tr>
                                    <th>Department:</th>
                                    <td><?php echo htmlspecialchars($complaint_data['department_name'] ?? 'Not assigned'); ?></td>
                                </tr>
                                <tr>
                                    <th>District:</th>
                                    <td><?php echo htmlspecialchars($complaint_data['district_name'] ?? 'Not assigned'); ?></td>
                                </tr>
                                <tr>
                                    <th>Description:</th>
                                    <td><?php echo htmlspecialchars($complaint_data['description']); ?></td>
                                </tr>
                                <tr>
                                    <th>Created At:</th>
                                    <td><?php echo htmlspecialchars($complaint_data['created_at']); ?></td>
                                </tr>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="container">
            <h3 class="text-center mb-4"><i class="fas fa-newspaper mr-2"></i> Office of the Premier News</h3>
            <div class="fb-page" 
                 data-href="https://web.facebook.com/profile.php?id=100063453018693" 
                 data-tabs="timeline" 
                 data-width="400" 
                 data-height="500" 
                 data-small-header="false" 
                 data-adapt-container-width="true" 
                 data-hide-cover="false" 
                 data-show-facepile="true">
                <blockquote cite="https://web.facebook.com/profile.php?id=100063453018693" class="fb-xfbml-parse-ignore">
                    <a href="https://web.facebook.com/profile.php?id=100063453018693">Office of the Premier - North West Province - South Africa</a>
                </blockquote>
            </div>
        </div>
    </div>

    <!-- Weather Updates Section -->
    <div class="section">
        <div class="container">
            <h3 class="text-center mb-4"><i class="fas fa-cloud-sun mr-2"></i> Weather Updates for North West Province</h3>
            <div id="weatherCarousel" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $chunks = array_chunk($weatherDataList, 4, true); // Split data into chunks of 4
                    foreach ($chunks as $index => $chunk): ?>
                        <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
                            <div class="row">
                                <?php foreach ($chunk as $city => $weatherData): ?>
                                    <div class="col-md-3 text-center mb-4">
                                        <div class="weather-card p-3 border rounded shadow">
                                            <h4><?php echo htmlspecialchars($weatherData['name']); ?></h4>
                                            <img src="https://openweathermap.org/img/wn/<?php echo htmlspecialchars($weatherData['weather'][0]['icon']); ?>@2x.png" alt="Weather Icon" class="weather-icon">
                                            <p>Temperature: <?php echo htmlspecialchars($weatherData['main']['temp']); ?>°C</p>
                                            <p>Condition: <?php echo htmlspecialchars($weatherData['weather'][0]['description']); ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <a class="carousel-control-prev" href="#weatherCarousel" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#weatherCarousel" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Include the Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v21.0&appId=858405609757222"></script>

    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <img src="images/provincial-logo.png" alt="Logo" height="30" class="mb-3">
                    <p>123 Main Street, City, Province</p>
                    <p>Email: info@nwcomplaint.gov.za</p>
                    <p>Phone: (123) 456-7890</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="publicDashboard.php" style="color: white;">Home</a></li>
                        <li><a href="about.php" style="color: white;">About</a></li>
                        <li><a href="faqs.php" style="color: white;">FAQs</a></li>
                        <li><a href="contact.php" style="color: white;">Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <p>Follow us on social media for updates and news.</p>
                    <!-- Add social media icons here -->
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2023 North West Complaint Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

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

    <!-- Chatbox HTML -->
    <div id="chatbox" class="chatbox">
        <div class="chatbox-header">
            <h5>Thato</h5>
            <button id="closeChat" class="btn-close" aria-label="Close"></button>
        </div>
        <div class="chatbox-body" id="chatboxBody">
            <div class="message bot-message">
                <div class="profile-pic" style="background-color: #28a745; display: flex; align-items: center; justify-content: center; border-radius: 50%; width: 40px; height: 40px; color: white; font-weight: bold; font-size: 20px;">🤖</div>
                <span>Hello! I'm Thato, your friendly assistant. How can I help you today?</span>
            </div>
        </div>
        <div class="chatbox-footer">
            <input type="text" id="userInput" placeholder="Type your message..." />
            <button id="sendMessage">Send</button>
        </div>
    </div>
    <button id="openChat" class="open-chat-btn">
        <i class="fas fa-comments"></i>
    </button>

    <!-- Chatbox JavaScript Logic -->
    <script>
        document.getElementById('openChat').onclick = function() {
            document.getElementById('chatbox').style.display = 'flex';
        };

        document.getElementById('closeChat').onclick = function() {
            document.getElementById('chatbox').style.display = 'none';
        };

        document.getElementById('sendMessage').onclick = function() {
            const userInput = document.getElementById('userInput').value;
            if (userInput.trim() === '') return; // Prevent sending empty messages

            // Display user message
            const userMessage = document.createElement('div');
            userMessage.className = 'message user-message';
            userMessage.innerHTML = `<span>${userInput}</span>`; // User message
            document.getElementById('chatboxBody').appendChild(userMessage);

            // Clear input
            document.getElementById('userInput').value = '';

            // Simulate bot response
            setTimeout(() => {
                const botMessage = document.createElement('div');
                botMessage.className = 'message bot-message';
                botMessage.innerHTML = `<div class="profile-pic" style="background-color: #28a745; display: flex; align-items: center; justify-content: center; border-radius: 50%; width: 40px; height: 40px; color: white; font-weight: bold; font-size: 20px;">🤖</div><span>${getBotResponse(userInput)}</span>`; // Bot message
                document.getElementById('chatboxBody').appendChild(botMessage);
                document.getElementById('chatboxBody').scrollTop = document.getElementById('chatboxBody').scrollHeight; // Scroll to bottom
            }, 500);
        };

        // FAQs data with personality
        const faqs = {
            "how to submit a complaint": "To submit a complaint, just head over to the 'Submit Complaint' section and fill out the form. It's super easy!",
            "what is the process for provincial complaints": "Provincial complaints are handled by our dedicated provincial team. Just make sure to provide all the necessary details!",
            "how long does it take to resolve a complaint": "We aim to resolve complaints within 72 hours. If it takes longer, feel free to check in with us!",
            "where can I find more information": "You can find more information in the FAQs section of our website. I'm here to help if you have more questions!",
            "what if my complaint is not addressed": "If your complaint isn't addressed in a timely manner, please reach out to us directly. We're here to help!",
            "can I track my complaint status?": "Absolutely! You can track your complaint status by entering your reference number in the 'Check Complaint Status' section.",
            "what types of complaints can I submit?": "You can submit various complaints related to services, facilities, or any issues you encounter. Just let us know!",
            "who do I contact for urgent issues?": "For urgent issues, please contact our support team directly via the contact page. We're here for you!"
        };

        // Simple bot response logic with personality
        function getBotResponse(input) {
            const lowerInput = input.toLowerCase();
            for (const question in faqs) {
                if (lowerInput.includes(question)) {
                    return faqs[question];
                }
            }
            return "Hmm, I'm not sure about that. But I'm always learning! You can check our FAQs or ask me something else.";
        }
    </script>
</body>
</html>