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

// Fetch all orders from the database
$sql = "SELECT * FROM order_details ORDER BY id DESC"; // Updated to use 'id' instead of 'order_id'
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Orders</title>
    <link rel="stylesheet" href="adorders.css">
    <script>
        function confirmStatusChange(orderId) {
            if (confirm('Order completed? Change status to "Delivered"?')) {
                // If confirmed, make an AJAX request to update the order status
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'update_status.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        alert('Order status updated to "Delivered"');
                        location.reload(); // Reload the page to reflect the changes
                    }
                };
                xhr.send('id=' + orderId);
            }
        }
    </script>
</head>
<body>

<div class="admin-container">
    <h2>Orders Management</h2>

    <?php if ($result->num_rows > 0): ?>
        <!-- Wrap table with a scrollable container -->
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Full Name</th>
                        <th>Mobile No.</th>
                        <th>Email</th>
                        <th>Delivery Address</th>
                        <th>Medicine Names</th>
                        <th>Prescription</th>
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['full_name']; ?></td>
                            <td><?php echo $row['mobile_no']; ?></td>
                            <td><?php echo $row['email']; ?></td>
                            <td><?php echo $row['delivery_address']; ?></td>
                            <td><?php echo $row['medicine_names']; ?></td>
                            <td>
                                <?php if ($row['prescription_file']): ?>
                                    <a href="../uploads/<?php echo $row['prescription_file']; ?>" target="_blank">View Prescription</a>
                                <?php else: ?>
                                    No Prescription
                                <?php endif; ?>
                            </td>
                            <td><?php echo $row['order_date']; ?></td>
                            <td><?php echo $row['order_status']; ?></td>
                            <td>
                                <svg onclick="confirmStatusChange(<?php echo $row['id']; ?>)" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p>No orders placed yet.</p>
    <?php endif; ?>
</div>
    </body>
</html>

<?php
// Close the connection
$conn->close();
?>
