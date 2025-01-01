<?php
// Start the session
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Your DB username
$password = "pass@123"; // Your DB password
$dbname = "user_data";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if the username exists in the database
    $sql = "SELECT id, username, full_name, email, phone_number, password FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, now verify password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Successful login
            // Store user details in session for future use
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['phone_number'] = $user['phone_number'];

            // Redirect to Order.php
            header("Location: /DoseTune/Order.php");
            exit();
        } else {
            // Invalid password
            echo "Invalid username or password.";
        }
    } else {
        // User not found
        echo "Invalid username or password.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
