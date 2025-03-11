<?php
session_start();
require_once 'db.php'; 

$error = ''; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = htmlspecialchars(trim($_POST['full_name']));

    try {
        $conn = getConnection();

        if ($conn === null) {
            throw new Exception("Database connection failed. Please try again later.");
        }
        // if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        if ($stmt === false) { // if preparation failed
            throw new Exception("Database error: " . $conn->error);
        }
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email already registered. Please use a different email.";
        } else {
            // Insert new user 
            $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, 'user')");
            if ($stmt === false) { // if preparation failed
                throw new Exception("Database error: " . $conn->error);
            }
            $stmt->bind_param("ssss", $username, $email, $password, $full_name);

            if ($stmt->execute()) {
                // Success
                $_SESSION['register_success'] = true;
                header('Location: login.php');
                exit();
            } else {
                $error = "Registration failed. Please try again later.";
            }
        }

    } catch (Exception $e) {
        // Handle exceptions
        error_log("Error: " . $e->getMessage()); 
        $error = "Error: " . $e->getMessage(); 
    } finally {
        if (isset($stmt) && $stmt !== false) { 
            $stmt->close(); 
        }
        if (isset($conn)) {
            $conn->close(); 
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="signUpage.css" rel="stylesheet">
</head>
<body>
    <div class="signup-box">
        <h1>SIGN UP</h1>

        <?php if (!empty($error)): ?>
            <div class="error-message" style="color: red;"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <div class="input-box">
                <input type="text" name="username" required>
                <label>Username</label>
            </div>
            <div class="input-box">
                <input type="email" name="email" required>
                <label>Email</label>
            </div>
            <div class="input-box">
                <input type="password" name="password" required>
                <label>Password</label>
            </div>
            <div class="input-box">
                <input type="text" name="full_name" required>
                <label>Full Name</label>
            </div>
            <div class="terms">
                <label><input type="checkbox" required> I agree to the Terms & Conditions</label>
            </div>
            <button type="submit">Sign Up</button>
        </form>
    </div>
</body>
</html>
