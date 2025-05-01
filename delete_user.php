<?php
session_start();
include 'db.php';

// Only admin can delete users
if ($_SESSION['user_role'] !== 'admin') {
    echo "You are not authorized to perform this action.";
    exit();
}

// Check if user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    
    // Step 1: Delete items associated with this user
    $delete_items_sql = "DELETE FROM items WHERE user_id = ?";
    $stmt = $conn->prepare($delete_items_sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    
    // Step 2: Delete the user
    $delete_user_sql = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_user_sql);
    $stmt->bind_param("i", $user_id);
    
    if ($stmt->execute()) {
        // Redirect back to user management page after successful deletion
        header("Location: admin_dashboard.php");
        exit();
    } else {
        echo "Error deleting user.";
    }
} else {
    echo "No user ID specified.";
}
?>
