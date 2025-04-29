<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $show_id = intval($_POST['show_id'] ?? 0);
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $date_added = date('Y-m-d H:i:s');

    if ($show_id <= 0 || $rating < 1 || $rating > 5 || empty($comment) || empty($status)) {
        header("Location: rate_show.php?error=invalid");
        exit;
    }

    // Check if the user already rated this show
    $stmt = $conn->prepare("SELECT * FROM User_Show_Data WHERE User_ID = ? AND Show_ID = ?");
    $stmt->bind_param("ii", $user_id, $show_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: rate_show.php?error=duplicate");
        exit;
    }
    $stmt->close();

    // Insert into User_Show_Data
    $stmt = $conn->prepare("INSERT INTO User_Show_Data (User_ID, Show_ID, Rating, Comment, Date_Added) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $user_id, $show_id, $rating, $comment, $date_added);
    $stmt->execute();
    $stmt->close();

    // Insert into User_Watchlist
    $stmt = $conn->prepare("INSERT INTO User_Watchlist (User_ID, Show_ID, Status, Date_Added) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $show_id, $status, $date_added);
    $stmt->execute();
    $stmt->close();

    header("Location: index.php?success=1");
    exit;
}

header("Location: rate_show.php");
exit;

