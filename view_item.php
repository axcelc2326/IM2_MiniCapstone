<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "Item not found.";
    exit();
}

$item = $conn->query("SELECT * FROM items WHERE id = '$id'")->fetch_assoc();
if (!$item) {
    echo "Item not found.";
    exit();
}

$dashboardLink = ($_SESSION['user_role'] === 'admin') ? 'admin_dashboard.php' : 'user_dashboard.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Item Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #0d1117;
            color: #c9d1d9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .details-box {
            background-color: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
            padding: 40px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        h2 {
            text-align: center;
            color: #58a6ff;
            margin-bottom: 20px;
        }

        p {
            margin: 10px 0;
            line-height: 1.5;
        }

        b {
            color: #58a6ff;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #58a6ff;
            font-size: 16px;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .image-preview {
            text-align: center;
            margin: 20px 0;
        }

        .image-preview img {
            max-width: 100%;
            max-height: 300px;
            width: auto;
            height: auto;
            border-radius: 8px;
            border: 1px solid #30363d;
            object-fit: contain;
        }
    </style>
</head>
<body>
    <div class="details-box">
        <h2><?= htmlspecialchars($item['title']) ?> (<?= ucfirst(htmlspecialchars($item['type'])) ?>)</h2>

        <?php if (!empty($item['image'])): ?>
            <div class="image-preview">
                <img src="<?= htmlspecialchars($item['image']) ?>" alt="Item Image">
            </div>
        <?php endif; ?>

        <p><b>Description:</b> <?= htmlspecialchars($item['description']) ?></p>
        <p><b>Location:</b> <?= htmlspecialchars($item['location']) ?></p>
        <p><b>Date:</b> <?= htmlspecialchars($item['date_posted']) ?></p>

        <a href="<?= $dashboardLink ?>">← Back to Dashboard</a>
    </div>
</body>
</html>
