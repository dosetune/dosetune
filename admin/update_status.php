<?php
// Database connection
$servername = "localhost"; // Change this to your database server
$username = "root"; // Change this to your database username
$password = "pass@123"; // Change this to your database password
$dbname = "orders"; // Change this to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the order ID is passed
if (isset($_POST['id'])) {
    $orderId = $_POST['id'];

    // Update the order status to "delivered"
    $sql = "UPDATE order_details SET order_status = 'delivered' WHERE id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Order status updated successfully.";
        } else {
            echo "Error updating order status.";
        }

        $stmt->close();
    } else {
        echo "Failed to prepare statement.";
    }
} else {
    echo "No order ID provided.";
}

$conn->close();
?>
