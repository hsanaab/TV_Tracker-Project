
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>


<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = trim($_POST['title'] ?? '');
    $release_year = intval($_POST['release_year'] ?? 0);
    $synopsis = trim($_POST['synopsis'] ?? '');
    $genres = $_POST['genres'] ?? [];
    $rating = intval($_POST['rating'] ?? 0);
    $comment = trim($_POST['comment'] ?? '');
    $status = trim($_POST['status'] ?? '');
    $user_id = $_SESSION['user_id'] ?? 0;

    if (
        empty($title) || $release_year <= 0 || empty($synopsis) ||
        count($genres) === 0 || $rating < 1 || $rating > 5 ||
        empty($comment) || empty($status) || $user_id === 0
    ) {
        die("All fields are required and must be valid.");
    }

    // Insert into TV_Shows
    $stmt = $conn->prepare("INSERT INTO TV_Shows (Title, Release_Year, Synopsis) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $title, $release_year, $synopsis);

    if (!$stmt->execute()) {
        die("Error adding show: " . $stmt->error);
    }

    $show_id = $stmt->insert_id;
    $stmt->close();

    // Insert into Show_Genres
    $stmt = $conn->prepare("INSERT INTO Show_Genres (Show_ID, Genre_ID) VALUES (?, ?)");
    foreach ($genres as $genre_id) {
        $stmt->bind_param("ii", $show_id, $genre_id);
        $stmt->execute();
    }
    $stmt->close();

    // Insert into User_Show_Data
    $review_date = date('Y-m-d');
    $stmt = $conn->prepare("INSERT INTO User_Show_Data (User_ID, Show_ID, Rating, Comment, Review_Date) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $user_id, $show_id, $rating, $comment, $review_date);

    if (!$stmt->execute()) {
        die("Error adding review: " . $stmt->error);
    }
    $stmt->close();

    // Insert into User_Watchlist
    $stmt = $conn->prepare("INSERT INTO User_Watchlist (User_ID, Show_ID, Status) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $show_id, $status);

    if (!$stmt->execute()) {
        die("Error adding to watchlist: " . $stmt->error);
    }
    $stmt->close();

    echo "Show added successfully! <a href='index.php'>Back to Home</a>";
}
$conn->close();
?>

