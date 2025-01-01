<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "pass@123";
$dbname = "orders";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit;
}

$full_name = $_SESSION['full_name'] ?? '';
$email = $_SESSION['email'] ?? '';
$phone_number = $_SESSION['phone_number'] ?? '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_full_name = $_POST['full-name'];
    $order_mobile_no = $_POST['mobile-no'];
    $order_email = $_POST['email'];
    $delivery_address = $_POST['delivery-address'];
    $medicine_names = $_POST['medicine-names'];

    // Handle file upload
    $prescription_file = '';
    if (isset($_FILES['prescription']) && $_FILES['prescription']['error'] == 0) {
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Sanitize the file name
        $file_name = preg_replace("/[^a-zA-Z0-9.]/", "_", basename($_FILES['prescription']['name']));
        $target_file = $upload_dir . $file_name;

        // Check file size
        if ($_FILES['prescription']['size'] > 10485760) {
            echo "Sorry, your file is too large.";
            exit;
        }

        // Allow specific file types
        $allowed_types = array("image/jpeg", "image/png", "application/pdf");
        if (!in_array($_FILES['prescription']['type'], $allowed_types)) {
            echo "Sorry, only JPG, PNG & PDF files are allowed.";
            exit;
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['prescription']['tmp_name'], $target_file)) {
            $prescription_file = $file_name;
        } else {
            echo "Sorry, there was an error uploading your file.";
            exit;
        }
    }

    // Insert data into the database
    $sql = "INSERT INTO order_details (full_name, mobile_no, email, delivery_address, medicine_names, prescription_file) VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssss", $order_full_name, $order_mobile_no, $order_email, $delivery_address, $medicine_names, $prescription_file);
        
        if ($stmt->execute()) {
            echo "<script>alert('Order has been placed successfully!'); window.location.href = 'order_confirmation.php';</script>";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    $conn->close();
}
?>

<!-- HTML Form remains unchanged -->



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DoseTune</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="logo.png" type="image/x-icon">
</head>
<body>
    <header>
        <div class="container">
            <img src="logo.png" alt="DoseTune Logo" class="logo">
            <nav>
                <ul class="menu">
                    <li><a href="index.html">Home</a></li>
                    <li><a href="services.html">Services</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="contactus.html">Contact</a></li>
                    <li><a href="Order.html" class="order-now">Order Now</a></li>
                    <?php if (isset($_SESSION['username'])): ?>
                    <!-- Show Log Out button if user is logged in -->
                    <li><a href="logout.php" class="logout">Log Out</a></li>
                <?php else: ?>
                    <!-- Show Log In button if user is not logged in -->
                    <li><a href="login/login.php" class="login">Log In</a></li>
                <?php endif; ?>

                </ul>
                <div class="hamburger" id="hamburger-icon">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </nav>
        </div>
    </header>

    <div class="order-now-container">
        <h2 style="font-weight: bold;">Order Now</h2>
        <form class="order-form" action="order.php" method="POST" enctype="multipart/form-data">
            <div class="form-row">
                <label for="full-name">Full Name:</label>
                <input type="text" id="full-name" name="full-name" value="<?php echo htmlspecialchars($full_name); ?>" required>
                
                <label for="mobile-no">Mobile No.:</label>
                <input type="tel" id="mobile-no" name="mobile-no" value="<?php echo htmlspecialchars($phone_number); ?>" required>
            </div>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            
            <label for="delivery-address">Delivery Address:</label>
            <textarea id="delivery-address" name="delivery-address" placeholder="Enter your delivery address" required></textarea>
            
            <label for="medicine-names">Medicine Names:</label>
            <textarea id="medicine-names" name="medicine-names" placeholder="Enter names of medicines" required></textarea>

            <label for="prescription">Upload Prescription: </label>
            <div class="upload-prescription-container">
                <div class="upload-area" onclick="document.getElementById('upload-input').click()">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                    </svg>
                    <p>Click to Upload Prescription</p>
                    <h5>PNG, JPG, PDF up to 10MB</h5>
                </div>
                <input type="file" id="upload-input" class="upload-input" name="prescription" accept="image/*, .pdf" />
            </div>
            
            <button type="submit" class="submit-btn">Submit Order</button>
        </form>
    </div>

<footer>
        <div class="container">
            <div class="footer-left">
                <h2>DoseTune</h2>
                <p>Your trusted partner in healthcare solutions. We provide quality medicines and exceptional service.</p>
            </div>
            <div class="footer-right">
                <p>Follow Us</p>
                <a href="#" aria-label="Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-facebook h-6 w-6">
                        <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                    </svg>
                </a>
                <a href="#" aria-label="Twitter">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-twitter h-6 w-6">
                        <path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"></path>
                    </svg>
                </a>
                <a href="https://www.instagram.com/dosetune?igsh=bHBrNmUwOGJiYXNi" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-instagram h-6 w-6">
                        <rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5"></line>
                    </svg>
                </a>
            </div>
        </div> 
        <div class="footer-bottom">
            <hr><br>
            <p>&copy; 2024 DoseTune. All rights reserved.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
