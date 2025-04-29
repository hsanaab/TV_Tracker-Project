
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>


<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ob_start(); // Important: Allows redirects after output

include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture all form inputs
    $title = trim($_POST['title']);
    $release_year = trim($_POST['release_year']);
    $synopsis = trim($_POST['synopsis']);
    $genres = $_POST['genres'] ?? []; // Array of selected genre IDs
    $rating = trim($_POST['rating']);
    $comment = trim($_POST['comment']);
    $status = trim($_POST['status']);
    $user_id = 1; // Static user for now

    // 1. Check if TV Show already exists
    $stmt = $conn->prepare("SELECT Show_ID FROM TV_Shows WHERE Title = ? AND Release_Year = ?");
    $stmt->bind_param("si", $title, $release_year);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Show already exists
        $row = $result->fetch_assoc();
        $show_id = $row['Show_ID'];
    } else {
        // Insert new TV Show
        $stmt = $conn->prepare("INSERT INTO TV_Shows (Title, Release_Year, Synopsis) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $title, $release_year, $synopsis);
        
        if ($stmt->execute()) {
            $show_id = $stmt->insert_id;
        } else {
            echo "Error inserting show: " . $stmt->error;
            exit;
        }
    }
    $stmt->close();

    // 2. Insert Genres (if any selected)
    if (!empty($genres)) {
        // Delete existing genres first
        $stmt = $conn->prepare("DELETE FROM Show_Genres WHERE Show_ID = ?");
        $stmt->bind_param("i", $show_id);
        $stmt->execute();
        $stmt->close();

        // Insert new genres
        $insert_genre = $conn->prepare("INSERT INTO Show_Genres (Show_ID, Genre_ID) VALUES (?, ?)");
        foreach ($genres as $genre_id) {
            $insert_genre->bind_param("ii", $show_id, $genre_id);
            $insert_genre->execute();
        }
        $insert_genre->close();
    }

    // 3. Insert into User_Show_Data (rating, comment)
    $stmt = $conn->prepare("INSERT INTO User_Show_Data (User_ID, Show_ID, Rating, Comment) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $user_id, $show_id, $rating, $comment);
    if (!$stmt->execute()) {
        echo "Error inserting review: " . $stmt->error;
        exit;
    }
    $stmt->close();

    // 4. Insert into User_Watchlist (watching status)
    $stmt = $conn->prepare("INSERT INTO User_Watchlist (User_ID, Show_ID, Status) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $user_id, $show_id, $status);
    if (!$stmt->execute()) {
        echo "Error inserting watchlist: " . $stmt->error;
        exit;
    }
    $stmt->close();

    // 5. Success: Redirect to Home
    header("Location: index.php");
    exit;
}

$conn->close();
?>

