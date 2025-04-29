<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title'] ?? '');
    $release_year = intval($_POST['release_year'] ?? 0);
    $synopsis = trim($_POST['synopsis'] ?? '');
    $genres = $_POST['genres'] ?? [];
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $user_id = $_SESSION['user_id'];
    $date_added = date('Y-m-d H:i:s');

    // Validate inputs
    if (empty($title) || $release_year <= 0 || empty($synopsis) ||
        count($genres) === 0 || $rating < 1 || $rating > 5 ||
        empty($comment) || empty($status)) {
        die("All fields are required and must be valid.");
    }

    // Check if title already exists
    $check_stmt = $conn->prepare("SELECT Show_ID FROM TV_Shows WHERE Title = ?");
    $check_stmt->bind_param("s", $title);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        header("Location: add_show.php?error=exists");
        exit;
    }
    $check_stmt->close();

    // Insert into TV_Shows
    $stmt = $conn->prepare("INSERT INTO TV_Shows (Title, Release_Year, Synopsis) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $title, $release_year, $synopsis);
    if (!$stmt->execute()) {
        die("Error inserting into TV_Shows: " . $stmt->error);
    }
    $show_id = $stmt->insert_id;
    $stmt->close();

    // Insert into Show_Genres
    $stmt = $conn->prepare("INSERT INTO Show_Genres (Show_ID, Genre_ID) VALUES (?, ?)");
    foreach ($genres as $genre_id) {
        $stmt->bind_param("ii", $show_id, $genre_id);
        if (!$stmt->execute()) {
            die("Error inserting into Show_Genres: " . $stmt->error);
        }
    }
    $stmt->close();

    // Insert into User_Show_Data
    $stmt = $conn->prepare("INSERT INTO User_Show_Data (User_ID, Show_ID, Rating, Comment, Date_Added) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $user_id, $show_id, $rating, $comment, $date_added);
    if (!$stmt->execute()) {
        die("Error inserting into User_Show_Data: " . $stmt->error);
    }
    $stmt->close();

    // Insert into User_Watchlist
    $stmt = $conn->prepare("INSERT INTO User_Watchlist (User_ID, Show_ID, Status, Date_Added) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $user_id, $show_id, $status, $date_added);
    if (!$stmt->execute()) {
        die("Error inserting into User_Watchlist: " . $stmt->error);
    }
    $stmt->close();

    header("Location: watchlist.php?success=1");
    exit;
}

$conn->close();
header("Location: add_show.php");
exit;

