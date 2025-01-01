<?php
// Start the session to manage session variables
session_start();

// Your database connection details
$servername = "localhost";
$username = "root"; // Your DB username
$password = "pass@123"; // Your DB password
$dbname = "user_data";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted and OTP is correct
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the OTP entered by the user
    $otp_entered = $_POST['otp'];
    
    // Check if the OTP exists in the session
    if (!isset($_SESSION['otp'])) {
        $_SESSION['error'] = 'No OTP found. Please request an OTP first.';
        header("Location: send_otp.php");
        exit();
    }

    // Check if the entered OTP matches the stored OTP and if the OTP hasn't expired
    if ($otp_entered == $_SESSION['otp'] && (time() - $_SESSION['otp_time']) <= 300) { // OTP expires in 5 minutes
        // OTP is correct and valid
        
        // Now gather the data to be inserted into the database
        $new_username = mysqli_real_escape_string($conn, $_SESSION['new_username']);
        $fullname = mysqli_real_escape_string($conn, $_SESSION['fullname']);
        $email = mysqli_real_escape_string($conn, $_SESSION['email']);
        $phone = mysqli_real_escape_string($conn, $_SESSION['phone']);
        $dob = mysqli_real_escape_string($conn, $_SESSION['dob']);
        $gender = mysqli_real_escape_string($conn, $_SESSION['gender']);
        $password = mysqli_real_escape_string($conn, $_SESSION['password']);
        
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Step 4: Check if the username, email, or phone already exists
        $sql = "SELECT * FROM users WHERE username=? OR email=? OR phone_number=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $new_username, $email, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // If a user with the same username, email, or phone already exists
            $_SESSION['error'] = 'Username, email, or phone already exists. Please choose another.';
            header("Location: signup.php");
            exit();
        }

        // Step 5: Insert the new user into the database
        $sql = "INSERT INTO users (username, full_name, email, phone_number, date_of_birth, gender, password) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $new_username, $fullname, $email, $phone, $dob, $gender, $hashed_password);

        if ($stmt->execute()) {
            // If registration is successful
            $_SESSION['success'] = 'You have successfully signed up. Please login to continue.';
            header("Location: login.php");  // Redirect to login page
            exit();
        } else {
            // If there is an error during registration
            $_SESSION['error'] = 'Error: Could not complete registration. Please try again.';
            echo "Error: " . $stmt->error;
            header("Location: signup.php");  // Redirect back to signup page in case of error
            exit();
        }
    } else {
        // OTP is incorrect or expired
        $_SESSION['error'] = 'Invalid or expired OTP. Please try again.';
        header("Location: verify_otp.php");  // Redirect back to OTP verification page
        exit();
    }
}

// Close database connections and statements
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <link rel="stylesheet" href="styles.css">  <!-- Add your custom styles here -->
</head>
<body>
    <div class="container">
        <h2>Verify OTP</h2>

        <!-- Show error message if OTP verification fails -->
        <?php
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>

        <!-- OTP verification form -->
        <form method="POST" action="verify_otp.php">
            <label for="otp">Enter OTP:</label>
            <input type="text" name="otp" id="otp" required>
            <button type="submit">Verify OTP</button>
        </form>

        <p><a href="send_otp.php">Resend OTP</a></p>  <!-- Link to resend OTP if needed -->
    </div>
</body>
</html>
