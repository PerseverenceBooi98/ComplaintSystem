<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection (make sure you have this)
require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Include the Composer autoload file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize inputs
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $street_name = mysqli_real_escape_string($conn, $_POST['street_name']);
    $township = mysqli_real_escape_string($conn, $_POST['township']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $complaint = mysqli_real_escape_string($conn, $_POST['complaint']);
    $jurisdiction = mysqli_real_escape_string($conn, $_POST['jurisdiction']);

    // Generate reference number
    $reference_number = generateReferenceNumber($conn, $jurisdiction, $_POST);

    // Prepare the query
    $query = "INSERT INTO complaints (reference_number, complainant_name, complainant_surname, complainant_email, complainant_phone, street_name, township, city, postal_code, description, jurisdiction";

    // Add jurisdiction-specific fields
    if ($jurisdiction == 'provincial') {
        $department_id = mysqli_real_escape_string($conn, $_POST['department']);
        $query .= ", department_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } elseif ($jurisdiction == 'district') {
        $district_id = mysqli_real_escape_string($conn, $_POST['district']);
        $query .= ", district_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    } elseif ($jurisdiction == 'municipal') {
        $district_id = mysqli_real_escape_string($conn, $_POST['district']);
        $municipality_id = mysqli_real_escape_string($conn, $_POST['municipality']);
        $query .= ", district_id, municipality_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    // Prepare and execute the statement
    $stmt = $conn->prepare($query);

    if ($jurisdiction == 'provincial') {
        $stmt->bind_param("sssssssssssi", $reference_number, $name, $surname, $email, $phone, $street_name, $township, $city, $postal_code, $complaint, $jurisdiction, $department_id);
    } elseif ($jurisdiction == 'district') {
        $stmt->bind_param("sssssssssssi", $reference_number, $name, $surname, $email, $phone, $street_name, $township, $city, $postal_code, $complaint, $jurisdiction, $district_id);
    } elseif ($jurisdiction == 'municipal') {
        $stmt->bind_param("sssssssssssii", $reference_number, $name, $surname, $email, $phone, $street_name, $township, $city, $postal_code, $complaint, $jurisdiction, $district_id, $municipality_id);
    }

    if ($stmt->execute()) {
        // Prepare email content
        $emailContent = "Thank you for submitting your complaint. Here are the details:\n\n" .
                        "Reference Number: " . $reference_number . "\n" .
                        "Name: " . $name . " " . $surname . "\n" .
                        "Email: " . $email . "\n" .
                        "Phone: " . $phone . "\n" .
                        "Address: " . $street_name . ", " . $township . ", " . $city . ", " . $postal_code . "\n" .
                        "Complaint: " . $complaint;

        // Create a new PHPMailer instance
        $mail = new PHPMailer(true);
        try {
            // Server settings
            $mail->isSMTP(); // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com'; // Specify main and backup SMTP servers
            $mail->SMTPAuth = true; // Enable SMTP authentication
            $mail->Username = 'kgotlellobafana@gmail.com'; // Your Gmail address
            $mail->Password = 'mfdrdroyaasapgdt'; // Your App Password (no spaces)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Enable TLS encryption
            $mail->Port = 587; // TCP port to connect to

            // Recipients
            $mail->setFrom('kgotlellobafana@gmail.com', 'Your Name'); // Your email and name
            $mail->addAddress($email, $name); // Add the user's email as the recipient

            // Content
            $mail->isHTML(false); // Set email format to plain text
            $mail->Subject = 'Your Complaint Submission';
            $mail->Body    = $emailContent;

            $mail->send(); // Send the email
            $response = array('status' => 'success', 'message' => "Complaint submitted successfully. Your reference number is: " . $reference_number);
        } catch (Exception $e) {
            $response = array('status' => 'error', 'message' => "Complaint submitted, but email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    } else {
        $response = array('status' => 'error', 'message' => "SQL Error: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    // Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

function generateReferenceNumber($conn, $jurisdiction, $postData) {
    $year = date('Y');
    $month = date('m');
    $day = date('d');

    if ($jurisdiction == 'provincial') {
        $dept_query = "SELECT SUBSTRING(department_name, 1, 3) as dept_code FROM departments WHERE department_id = ?";
        $stmt = $conn->prepare($dept_query);
        $stmt->bind_param("i", $postData['department']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $dept_code = strtoupper($row['dept_code']);
        $reference = "PROV{$dept_code}{$year}{$month}{$day}";
    } elseif ($jurisdiction == 'district') {
        $dist_query = "SELECT SUBSTRING(district_name, 1, 4) as dist_code FROM districts WHERE district_id = ?";
        $stmt = $conn->prepare($dist_query);
        $stmt->bind_param("i", $postData['district']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $dist_code = strtoupper($row['dist_code']);
        $reference = "DIST{$dist_code}{$year}{$month}{$day}";
    } elseif ($jurisdiction == 'municipal') {
        $muni_query = "SELECT SUBSTRING(municipality_name, 1, 4) as muni_code FROM municipalities WHERE municipality_id = ?";
        $stmt = $conn->prepare($muni_query);
        $stmt->bind_param("i", $postData['municipality']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $muni_code = strtoupper($row['muni_code']);
        $reference = "MUNI{$muni_code}{$year}{$month}{$day}";
    }

    // Add a unique identifier to ensure uniqueness
    $reference .= sprintf("%04d", mt_rand(0, 9999));

    return $reference;
}