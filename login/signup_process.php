<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "pass@123";
$dbname = "user_data";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get user input from the form
$new_username = mysqli_real_escape_string($conn, $_POST['username']);
$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$dob = mysqli_real_escape_string($conn, $_POST['dob']);
$gender = mysqli_real_escape_string($conn, $_POST['gender']);
$password = mysqli_real_escape_string($conn, $_POST['password']);
$otp_entered = mysqli_real_escape_string($conn, $_POST['otp']);  // OTP entered by the user

// Step 1: Check if OTP is valid
if (!isset($_SESSION['otp']) || $otp_entered != $_SESSION['otp'] || (time() - $_SESSION['otp_time']) > 300) {
    $_SESSION['error'] = 'Invalid or expired OTP. Please try again.';
    header("Location: login.php?error=otp");  // Redirect back to login page with error
    exit();
}

// Hash the password before storing it
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Step 2: Check if the username, email, or phone already exists

// Check for existing username
$sql = "SELECT id FROM users WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $new_username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION['error'] = 'Username already exists. Please choose a different username.';
    header("Location: login.php?error=username");
    exit();
}

// Check for existing email
$sql = "SELECT id FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION['error'] = 'Email already exists. Please choose a different email.';
    header("Location: login.php?error=email");
    exit();
}

// Check for existing phone number
$sql = "SELECT id FROM users WHERE phone_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $phone);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $_SESSION['error'] = 'Phone number already exists. Please choose a different phone number.';
    header("Location: login.php?error=phone");
    exit();
}

// Default values for OTP-related fields
$otp = ''; // Empty string for OTP
$otp_expiry = date('Y-m-d H:i:s', time() - 1); // A past timestamp for placeholder

// Step 3: Insert the new user into the database
$sql = "INSERT INTO users (username, full_name, email, phone_number, date_of_birth, gender, password, otp, otp_expiry) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssss", 
    $new_username, 
    $fullname, 
    $email, 
    $phone, 
    $dob, 
    $gender, 
    $hashed_password, 
    $otp, 
    $otp_expiry
);

// Execute the query and handle potential errors
if ($stmt->execute()) {
    $_SESSION['success'] = 'You have successfully signed up. Please log in to continue.';
    header("Location: login.php?success=signup");
    exit();
    
} else {
    $_SESSION['error'] = 'Error: Could not complete registration. Please try again.';
    error_log("SQL Error: " . $stmt->error);
    header("Location: login.php?error=signup");
    exit();
}

// Close database connections
$stmt->close();
$conn->close();
?>
