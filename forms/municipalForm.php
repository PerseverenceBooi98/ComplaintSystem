<?php
require_once '../config.php';
$current_page = 'municipal';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Municipal Complaint Form - NW Complaint Management System</title>
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
        .navbar-brand img {
            max-height: 40px;
            width: auto;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="../publicDashboard.php">
                <img src="../images/provincial-logo.png" alt="NW Province Logo" class="img-fluid">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'home') ? 'active' : ''; ?>" href="../publicDashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'about') ? 'active' : ''; ?>" href="../about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'faqs') ? 'active' : ''; ?>" href="../faqs.php">FAQs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'contact') ? 'active' : ''; ?>" href="../contact.php">Contact</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Language
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

    <div class="container mt-5">
        <h2>Municipal Complaint Form</h2>
        <form action="../submit_complaint.php" method="post" id="complaintForm">
            <input type="hidden" name="complaint_type" value="municipal">
            <input type="hidden" name="jurisdiction" value="municipal">
            <div class="mb-3">
                <label for="name" class="form-label">First Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Last Name</label>
                <input type="text" class="form-control" id="surname" name="surname" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="email" name="email" required>
                <div id="emailError" class="error"></div>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" class="form-control" id="phone" name="phone" required pattern="0[0-9]{9}" title="Phone number must be 10 digits and start with 0">
                <div id="phoneError" class="error"></div>
            </div>
            <div class="mb-3">
                <label for="street_name" class="form-label">Street Name</label>
                <input type="text" class="form-control" id="street_name" name="street_name" required>
            </div>
            <div class="mb-3">
                <label for="township" class="form-label">Township</label>
                <input type="text" class="form-control" id="township" name="township" required>
            </div>
            <div class="mb-3">
                <label for="city" class="form-label">City</label>
                <input type="text" class="form-control" id="city" name="city" required>
            </div>
            <div class="mb-3">
                <label for="postal_code" class="form-label">Postal Code</label>
                <input type="text" class="form-control" id="postal_code" name="postal_code" required>
            </div>
            <div class="mb-3">
                <label for="district" class="form-label">District</label>
                <select class="form-select" id="district" name="district" required>
                    <option value="">Select a district</option>
                    <?php
                    $district_query = "SELECT district_id, district_name FROM districts";
                    $district_result = $conn->query($district_query);
                    while ($district_row = $district_result->fetch_assoc()) {
                        echo "<option value='" . $district_row['district_id'] . "'>" . $district_row['district_name'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="municipality" class="form-label">Municipality</label>
                <select class="form-select" id="municipality" name="municipality" required>
                    <option value="">Select a municipality</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="complaint" class="form-label">Complaint Details</label>
                <textarea class="form-control" id="complaint" name="complaint" rows="5" required></textarea>
                <div id="complaintError" class="error"></div>
            </div>
            <button type="submit" class="btn btn-primary">Submit Complaint</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#district').change(function() {
            var districtId = $(this).val();
            if(districtId) {
                $.ajax({
                    url: '../get_municipalities.php',
                    type: 'POST',
                    data: {district_id: districtId},
                    success: function(html) {
                        $('#municipality').html(html);
                    }
                }); 
            } else {
                $('#municipality').html('<option value="">Select a municipality</option>');
            }
        });
    });

    $(document).ready(function() {
        $('#complaintForm').submit(function(e) {
            e.preventDefault();
            
            // Perform client-side validation here
            let isValid = true;
            const email = $('#email').val();
            const phone = $('#phone').val();
            const complaint = $('#complaint').val();

            // Email validation
            if (email.indexOf('@') === -1) {
                $('#emailError').text('Email must contain @');
                isValid = false;
            } else {
                $('#emailError').text('');
            }

            // Phone validation
            if (!phone.match(/^0[0-9]{9}$/)) {
                $('#phoneError').text('Phone number must be 10 digits and start with 0');
                isValid = false;
            } else {
                $('#phoneError').text('');
            }

            // Complaint validation
            if (complaint.trim() === '') {
                $('#complaintError').text('Complaint description is required');
                isValid = false;
            } else {
                $('#complaintError').text('');
            }

            if (isValid) {
                $.ajax({
                    url: '../submit_complaint.php',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            alert(response.message);
                            $('#complaintForm')[0].reset();
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function() {
                        alert('An error occurred while submitting the complaint.');
                    }
                });
            }
        });
    });
    </script>
</body>
</html>