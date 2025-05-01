<?php
session_start();
include 'db.php';

if ($_SESSION['user_role'] !== 'admin') {
    die("You don't have permission to view this page.");
}

$items = $conn->query("SELECT * FROM items");
$users = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #0d1117;
            color: #c9d1d9;
        }

        .navbar {
            background-color: #161b22;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #30363d;
        }

        .navbar h1 {
            margin: 0;
            font-size: 1.5rem;
            color: #58a6ff;
        }

        .nav-links a {
            color: #8b949e;
            margin-left: 1.5rem;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .nav-links a:hover {
            color: #58a6ff;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        h2 {
            color: #58a6ff;
            margin-top: 40px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background-color: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
            padding: 15px 20px;
            margin-bottom: 15px;
            transition: transform 0.2s;
        }

        li:hover {
            transform: scale(1.01);
        }

        .actions a {
            text-decoration: none;
            color: #58a6ff;
            margin-right: 15px;
            font-size: 0.9rem;
        }

        .actions a:hover {
            color: #79c0ff;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h1>Admin Dashboard</h1>
        <div class="nav-links">
            <a href="logout.php">🚪 Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Manage Items</h2>
        <?php if ($items->num_rows > 0): ?>
            <ul>
                <?php while($row = $items->fetch_assoc()): ?>
                    <li>
                        <strong><?= htmlspecialchars($row['title']) ?></strong> — <?= htmlspecialchars($row['location']) ?> 
                        <div class="actions">
                            <a href="view_item.php?id=<?= $row['id'] ?>">🔍 View</a>
                            <a href="edit_item.php?id=<?= $row['id'] ?>">✏️ Edit</a>
                            <a href="delete_item.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this item?');">🗑️ Delete</a>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No items available.</p>
        <?php endif; ?>

        <h2>Manage Users</h2>
        <?php if ($users->num_rows > 0): ?>
            <ul>
                <?php while($row = $users->fetch_assoc()): ?>
                    <li>
                        <strong><?= htmlspecialchars($row['name']) ?></strong> (<?= htmlspecialchars($row['email']) ?>)
                        <div class="actions">
                            <?php if ($row['id'] !== $_SESSION['user_id']): // Admin cannot delete themselves ?>
                                <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this user?');">🗑️ Delete</a>
                            <?php else: ?>
                                <span> (You cannot 🗑️delete your own account)</span>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>No users available.</p>
        <?php endif; ?>
    </div>
</body>
</html>
