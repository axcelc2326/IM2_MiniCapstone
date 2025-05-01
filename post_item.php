<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $desc = trim($_POST['description']);
    $type = $_POST['type'];
    $loc = trim($_POST['location']);
    $uid = $_SESSION['user_id'];
    $imagePath = null;

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $uploadDir = 'uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = time() . '_' . basename($_FILES['image']['name']);
        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $imagePath = $targetFile;
        }
    }

    $stmt = $conn->prepare("INSERT INTO items (user_id, title, description, type, location, image, date_posted) 
                            VALUES (?, ?, ?, ?, ?, ?, CURDATE())");
    $stmt->bind_param("isssss", $uid, $title, $desc, $type, $loc, $imagePath);
    $stmt->execute();
    $stmt->close();

    $redirectPage = ($_SESSION['user_role'] === 'admin') ? 'admin_dashboard.php' : 'user_dashboard.php';
    header("Location: $redirectPage");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Post Item</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: 'Inter', sans-serif;
        background-color: #0d1117;
        color: #c9d1d9;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 20px;
    }

    .post-box {
        background-color: #161b22;
        border: 1px solid #30363d;
        border-radius: 16px;
        padding: 30px;
        max-width: 600px;
        width: 100%;
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.35);
    }

    h2 {
        text-align: center;
        color: #58a6ff;
        margin-bottom: 25px;
        font-size: 24px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        font-size: 15px;
    }

    input[type="text"],
    textarea,
    select,
    input[type="file"] {
        width: 100%;
        padding: 14px;
        margin-bottom: 20px;
        background-color: #0d1117;
        border: 1px solid #30363d;
        border-radius: 8px;
        color: #c9d1d9;
        font-size: 15px;
        transition: border-color 0.2s;
    }

    input:focus,
    textarea:focus,
    select:focus {
        border-color: #58a6ff;
        outline: none;
    }

    textarea {
        resize: vertical;
        min-height: 120px;
    }

    button {
        width: 100%;
        padding: 14px;
        background-color: #238636;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    button:hover {
        background-color: #2ea043;
    }

    .back-link {
        text-align: center;
        margin-top: 20px;
    }

    .back-link a {
        color: #58a6ff;
        text-decoration: none;
        font-size: 14px;
    }

    .back-link a:hover {
        text-decoration: underline;
    }
</style>

</head>
<body>
    <div class="post-box">
        <h2>📌 Post Lost/Found Item</h2>
        <form method="post" enctype="multipart/form-data">
            <input type="text" name="title" placeholder="Item Title" required>
            <textarea name="description" placeholder="Description" required></textarea>
            <select name="type" required>
                <option value="" disabled selected>Type (Lost or Found)</option>
                <option value="lost">Lost</option>
                <option value="found">Found</option>
            </select>
            <input type="text" name="location" placeholder="Location" required>
            <input type="file" name="image" accept="image/*">
            <button type="submit">Post Item</button>
        </form>
        <div class="back-link">
            <?php
            $backLink = ($_SESSION['user_role'] === 'admin') ? 'admin_dashboard.php' : 'user_dashboard.php';
            ?>
            <p><a href="<?= $backLink ?>">← Back to Dashboard</a></p>
        </div>
    </div>
</body>
</html>
