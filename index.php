<?php
session_start();
include 'db.php'; // Include database 

// database connection
$conn = getConnection();

//if successful
if ($conn === null) {
    die("Database connection failed.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Shadows+Into+Light&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Nav Bar -->
    <nav>
        <div class="logo">LOST & FOUND</div>
        <ul>
            <li><a href="#report">REPORT ITEMS</a></li>
            <li><a href="#listings">LISTINGS</a></li>
            <li><a href="#contact">CONTACT</a></li>
            
            <?php if (!isset($_SESSION['username'])): ?>
                <li><a href="signup.php">SIGN UP</a></li>
                <li><a href="login.php">LOGIN</a></li>
            <?php else: ?>
                <li><a href="logout.php">Log Out (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Lost Something? Or Found Something?</h1>
            <p>We help you reunite with your lost items or claim found ones!</p>
            <a href="#report" class="btn">Report</a>
        </div>
    </section>

    <!-- Report Item -->
    <section id="report" class="report-section">
        <h2>Report a Lost or Found Item</h2>
        <form action="submit_report.php" method="POST" id="reportForm" enctype="multipart/form-data">
            <div class="form-group">
                <label for="itemName">Item Name</label>
                <input type="text" id="itemName" name="item_name" required>
            </div>
            <div class="form-group">
                <label for="itemType">Item Type</label>
                <select id="itemType" name="item_type" required>
                    <option value="Lost">Lost</option>
                    <option value="Found">Found</option>
                </select>
            </div>
            <div class="form-group">
                <label for="itemDescription">Description</label>
                <textarea id="itemDescription" name="item_description" required></textarea>
            </div>
            <div class="form-group">
                <label for="itemImage">Item Image</label>
                <input type="file" id="itemImage" name="item_image" accept="image/*" />
            </div>
            <button type="submit" class="btn">Submit Report</button>
        </form>
    </section>
    
    <!-- List Items -->
    <section id="listings" class="listings">
        <h2>Browse Lost & Found Items</h2>
        <div class="items-list">

            <?php
            // Fetch items
            $sql = "SELECT * FROM items";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="item">';
                    echo '<h3>' . htmlspecialchars($row['name']) . '</h3>';
                    echo '<p><strong>Type:</strong> ' . htmlspecialchars($row['type']) . '</p>';
                    echo '<p><strong>Description:</strong> ' . htmlspecialchars($row['description']) . '</p>';
                    echo '<a href="edit.php?id=' . $row['id'] . '" class="btn">Edit</a>';
                    echo '<a href="delete.php?id=' . $row['id'] . '" class="btn">Delete</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>No items found.</p>";
            }
            ?>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact">
        <h2>Contact Us</h2>
        <p>If you need assistance, feel free to reach out.</p>
        <p>Email: <a href="mailto:aadi000000000000@gmail.com">aadi000000000000@gmail.com</a></p>
    </section>

    <script src="scripts.js"></script>
</body>
</html>
