<?php
// Start session and check if the user is an admin
session_start();

// Database connection
$servername = "localhost";
$username = "root"; // Your DB username
$password = "pass@123"; // Your DB password
$dbname = "user_data";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$sql = "SELECT id, username, full_name, email, phone_number, date_of_birth, gender, created_at FROM users";
$result = $conn->query($sql);

?>

<?php
// Check if the delete form has been submitted
if (isset($_POST['delete_user']) && isset($_POST['delete_id'])) {
    // Get the user ID to delete
    $delete_id = $_POST['delete_id'];

    // Prepare the DELETE SQL query
    $delete_sql = "DELETE FROM users WHERE id = ?";

    // Create a prepared statement
    if ($stmt = $conn->prepare($delete_sql)) {
        // Bind the parameter
        $stmt->bind_param("i", $delete_id);

        // Execute the query
        if ($stmt->execute()) {
            // Success: Redirect to the admin dashboard page
            header("Location: admin.php");
            exit();
        } else {
            // Error in deletion
            echo "Error deleting record: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - DoseTune</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="icon" href="../logo.png" type="image/x-icon">
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container">
            <img src="../logo.png" alt="DoseTune Logo" class="logo">
            <nav>
                <ul class="menu">
                    <li><a href="../index.html">Home</a></li>
                    <li><a href="../services.html">Services</a></li>
                    <li><a href="../about.html">About</a></li>
                    <li><a href="../contactus.html">Contact</a></li>
                    <li><a href="../redirect.php" class="order-now">Order Now</a></li>
                </ul>
                <div class="hamburger" id="hamburger-icon">
                    <div class="bar"></div>
                    <div class="bar"></div>
                    <div class="bar"></div>
                </div>
            </nav>
        </div>
    </header>

<!-- Admin Dashboard Body Section -->
<section class="dashboard-section">
    <div class="container">
        <h1>Admin Dashboard</h1>
        <h2>Registered Users</h2>
        <div class="table-wrapper">
        <table border="1" cellspacing="0" cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone Number</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Created At</th>
                    <!--  Delete Column -->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Assuming $result is already fetched from the database
                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['username']); ?></td>
                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_of_birth']); ?></td>
                            <td><?php echo htmlspecialchars($row['gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <!-- Delete button -->
                            <td>
                    <form action="admin.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                        <input type="hidden" name="delete_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="delete_user" class="delete-btn"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
</svg>
</button>
                    </form>
                </td>
                        </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                        <td colspan="8">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
                </div>
    </div>
</section>


    <!-- Footer Section -->
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
<?php
$conn->close();
?>
