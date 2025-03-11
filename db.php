<?php
function getConnection() {
    // Database
    $host = 'localhost';
    $user = 'root';
    $password = 'q#Z19@xz';
    $dbname = 'lost_found';

    // connect
    $conn = new mysqli($host, $user, $password, $dbname);

    // Check for errors
    if ($conn->connect_error) {
        // Log error to a file
        file_put_contents('db_error_log.txt', date('Y-m-d H:i:s') . " - Connection failed: " . $conn->connect_error . "\n", FILE_APPEND);
        
        throw new Exception("Database connection failed. Please try again later.");
    }

    $conn->set_charset('utf8mb4');

    return $conn;
}
?>
