<?php
// Include the db.php
include 'db.php';  

session_start();

//error variable
$error = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // connection 
    $conn = getConnection();

    if ($conn === null) {
        die("Database connection failed.");
    }

    // Check existing users
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql); 
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username']; 
            header("Location: index.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "No such user found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOGIN</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Shadows+Into+Light&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lobster&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="loginPage.css">
</head>
<body>
    <!-- Nav Bar -->
    <nav>
    <div class="logo" onclick="window.location.href='index.php';">LOST & FOUND</div>
        <ul>
            <?php if (!isset($_SESSION['username'])): ?>
            <?php else: ?>
                <li><a href="logout.php">Log Out (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a></li>
            <?php endif; ?>
        </ul>
    </nav>

    <!-- Main (Login Form) -->
    <main>
        <div class="login-box">
            <h1>LOGIN</h1>

            <?php if (!empty($error)): ?>
                <div class="error-message" style="color: red;"><?php echo $error; ?></div>
            <?php endif; ?>

            <form action="login.php" method="POST">
                <div class="input-box">
                    <input type="text" name="username" required>
                    <label>Username</label>
                </div>
                <div class="input-box">
                    <input type="password" name="password" required>
                    <label>Password</label>
                </div>
                <div class="remember-forgot">
                    <label><input type="checkbox"> Remember me</label>
                    <a href="#">Forgot Password?</a>
                </div>
                <button type="submit">Login</button>
            </form>
        </div>
    </main>
</body>
</html>
