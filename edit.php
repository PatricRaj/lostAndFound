<?php
include 'db.php';
if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    // Fetch items
    $conn = getConnection();
    $sql = "SELECT * FROM items WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $item = $result->fetch_assoc();
    $stmt->close();
} else {
    // If no item ID is provided
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Item</title>
    <link rel="stylesheet" href="edit.css"> 
</head>
<body>
    <!-- Nav Bar -->
    <nav>
        <div class="logo">
            EDIT ITEM
        </div>
    </nav>

    <!-- Main -->
    <main>
        <div class="edit-box">
            <form action="update_item.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">

                <div class="input-box">
                    <label for="item_name">ITEM NAME</label>
                    <input type="text" id="item_name" name="item_name" value="<?php echo htmlspecialchars($item['name']); ?>" required>
                </div>

                <div class="input-box">
                    <label for="item_type">ITEM TYPE</label>
                    <input type="text" id="item_type" name="item_type" value="<?php echo htmlspecialchars($item['type']); ?>" required>
                </div>

                <div class="input-box">
                    <label for="item_description">ITEM DESCRIPTION</label>
                    <textarea id="item_description" name="item_description" required><?php echo htmlspecialchars($item['description']); ?></textarea>
                </div>

                <div class="input-box">
                    <label for="item_image">IMAGE(Optional)</label>
                    <input type="file" id="item_image" name="item_image">
                    <img src="<?php echo $item['image']; ?>" alt="Current Image" width="100" height="100">
                </div>

                <button type="submit">Update Item</button>
            </form>
        </div>
    </main>
</body>
</html>
