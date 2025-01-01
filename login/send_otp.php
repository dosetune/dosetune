<?php
// Start the session to store OTP and time
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Load PHPMailer and necessary classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Get email from POST data
$data = json_decode(file_get_contents("php://input"), true);
$email = $data['email'] ?? '';  // Use null coalescing operator to handle empty email

// Validate the email
if (empty($email)) {
    echo json_encode(['success' => false, 'error' => 'Email is required']);
    exit();  // Exit the script if email is not provided
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "pass@123";
$dbname = "user_data";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'error' => 'Database connection failed']);
    exit();
}

// Check if email is registered
$sql = "SELECT id FROM users WHERE email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(['success' => false, 'error' => 'Email not registered']);
    exit();
}

// Generate OTP
$otp = rand(100000, 999999);  // Generate a random 6-digit OTP
$_SESSION['otp'] = $otp;
$_SESSION['otp_time'] = time();  // Store OTP time for expiry validation
$_SESSION['email'] = $email;

// Configure PHPMailer for SMTP with Brevo (Sendinblue)
$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp-relay.brevo.com';
    $mail->SMTPAuth = true;
    $mail->Username = '82a5e2001@smtp-brevo.com';  // SMTP username
    $mail->Password = '67tXLMnWvwFVjKAh';  // SMTP password (update before production)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;  // Use port 587 for TLS

    // Recipients
    $mail->setFrom('vinay123bhadane@gmail.com', 'DoseTune');  // Use a valid email here
    $mail->addAddress($email);

    // Email Content
    $mail->isHTML(true);
    $mail->Subject = 'Your OTP Code';
    $mail->Body    = "Your OTP code is: <strong>$otp</strong>";

    // Send email
    $mail->send();
    echo json_encode(['success' => true, 'message' => 'OTP sent successfully to your E-mail...!']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Mailer Error: ' . $mail->ErrorInfo]);
}

// Close database connection
$stmt->close();
$conn->close();
?>
