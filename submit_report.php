<?php
include 'db.php';  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $item_type = $_POST['item_type'];
    $item_description = $_POST['item_description'];

    try {
        $conn = getConnection(); 

        // Upload file
        if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
            $uploadDirectory = 'uploads/';
            $uploadFile = $uploadDirectory . basename($_FILES['item_image']['name']);
            
            $check = getimagesize($_FILES['item_image']['tmp_name']);
            if ($check !== false) {
                if (move_uploaded_file($_FILES['item_image']['tmp_name'], $uploadFile)) {
                    $sql = "INSERT INTO items (name, type, description, image) VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    if ($stmt === false) {
                        throw new Exception("Error preparing the SQL query: " . $conn->error);
                    }
                    //bind param
                    $stmt->bind_param("ssss", $item_name, $item_type, $item_description, $uploadFile);

                    if ($stmt->execute()) {
                        echo "Your report has been submitted successfully!";
                    } else {
                        echo "Error: " . $stmt->error;
                    }

                    $stmt->close();
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } else {
                echo "The uploaded file is not a valid image.";
            }
        } else {
            echo "No file uploaded or there was an error with the file.";
        }
        $conn->close();
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>
