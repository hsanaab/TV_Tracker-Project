<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connect to database
include('db_connect.php');

// Initialize search parameters with defaults
$title_search = $_GET['title_search'] ?? '';
$genre_search = $_GET['genre_search'] ?? '';
$min_rating = $_GET['min_rating'] ?? 1;

// Prepare SQL
$sql = "SELECT
    U.Username,
    TS.Title,
    GROUP_CONCAT(G.Genre_Name) AS Genres,
    USD.Rating,
    USD.Comment,
    USD.Date_Added
FROM
    User_Show_Data USD
JOIN
    Users U ON USD.User_ID = U.User_ID
JOIN
    TV_Shows TS ON USD.Show_ID = TS.Show_ID
JOIN
    Show_Genres SG ON TS.Show_ID = SG.Show_ID
JOIN
    Genres G ON SG.Genre_ID = G.Genre_ID
WHERE
    TS.Title LIKE ?
    AND G.Genre_Name LIKE ?
    AND USD.Rating >= ?
GROUP BY
    USD.ID
ORDER BY
    USD.Date_Added DESC";

$stmt = $conn->prepare($sql);
$like_title = "%$title_search%";
$like_genre = "%$genre_search%";
$stmt->bind_param('ssi', $like_title, $like_genre, $min_rating);

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Reviews</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>
<body>

<h1>🔎 Browse TV Show Reviews</h1>

<div class="navigation">
    <a href="index.php" class="btn">🏠 Back to Home</a>
</div>

<!-- Search Form -->
<form method="GET" style="max-width: 600px; margin: 0 auto;">
    <input type="text" name="title_search" placeholder="Search Title" value="<?php echo htmlspecialchars($title_search); ?>">

    <select name="genre_search">
        <option value="">-- Any Genre --</option>
        <?php
        $genre_query = "SELECT Genre_Name FROM Genres ORDER BY Genre_Name";
        $genre_result = $conn->query($genre_query);
        while ($genre = $genre_result->fetch_assoc()) {
            $selected = ($genre['Genre_Name'] == $genre_search) ? 'selected' : '';
            echo "<option value='" . htmlspecialchars($genre['Genre_Name']) . "' $selected>" . htmlspecialchars($genre['Genre_Name']) . "</option>";
        }
        ?>
    </select>

    <select name="min_rating">
        <option value="1" <?php if ($min_rating == 1) echo 'selected'; ?>>1+ Stars</option>
        <option value="2" <?php if ($min_rating == 2) echo 'selected'; ?>>2+ Stars</option>
        <option value="3" <?php if ($min_rating == 3) echo 'selected'; ?>>3+ Stars</option>
        <option value="4" <?php if ($min_rating == 4) echo 'selected'; ?>>4+ Stars</option>
        <option value="5" <?php if ($min_rating == 5) echo 'selected'; ?>>5 Stars Only</option>
    </select>

    <button type="submit">Search</button>
    <a href="browse_reviews.php" class="btn">Reset</a>
</form>

<!-- Results Table -->
<?php if ($result->num_rows > 0): ?>
    <table>
        <tr>
            <th>User</th>
            <th>Show Title</th>
            <th>Genres</th>
            <th>Rating</th>
            <th>Comment</th>
            <th>Date Added</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['Username']); ?></td>
            <td><?php echo htmlspecialchars($row['Title']); ?></td>
            <td><?php echo htmlspecialchars($row['Genres']); ?></td>
            <td><?php echo htmlspecialchars($row['Rating']); ?></td>
            <td><?php echo htmlspecialchars($row['Comment']); ?></td>
            <td><?php echo htmlspecialchars($row['Date_Added']); ?></td>
        </tr>
        <?php endwhile; ?>

    </table>
<?php else: ?>
    <p style="text-align: center; color: #1A1A40;">No reviews found matching your search.</p>
<?php endif; ?>

</body>
</html>

