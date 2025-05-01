<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'] ?? null;
$uid = $_SESSION['user_id'];
$role = $_SESSION['user_role'] ?? 'user';

if (!$id) {
    // Invalid request
    header("Location: " . ($role === 'admin' ? 'admin_dashboard.php' : 'user_dashboard.php'));
    exit();
}

// Prepare and delete
if ($role === 'admin') {
    // Admins can delete any item
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ?");
    $stmt->bind_param("i", $id);
} else {
    // Users can only delete their own items
    $stmt = $conn->prepare("DELETE FROM items WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $id, $uid);
}

$stmt->execute();
$stmt->close();

// Redirect to the appropriate dashboard
$redirect = ($role === 'admin') ? 'admin_dashboard.php' : 'user_dashboard.php';
header("Location: $redirect");
exit();
