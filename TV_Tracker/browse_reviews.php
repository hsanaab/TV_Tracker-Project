
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>


<?php
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
<html>
<head>
    <title>Browse Reviews</title>
    <link rel="stylesheet" href="styles.css"> <!-- Optional styling -->
</head>
<body>
    <h1>Browse TV Show Reviews</h1>

    <!-- Navigation Link -->
    <a href="index.php" class="btn">🏠 Back to Main Page</a>

    <!-- Search Form -->
    <form method="GET" action="browse_reviews.php">
        <input type="text" name="title_search" placeholder="Search Title" value="<?php echo htmlspecialchars($title_search); ?>">
        
        <select name="genre_search">
            <option value="">-- Any Genre --</option>
            <option value="Drama">Drama</option>
            <option value="Comedy">Comedy</option>
            <option value="Science Fiction">Science Fiction</option>
            <option value="Fantasy">Fantasy</option>
            <option value="Crime Fiction">Crime Fiction</option>
            <option value="Horror">Horror</option>
            <option value="Romance">Romance</option>
            <option value="Thriller">Thriller</option>
            <option value="Mystery">Mystery</option>
            <option value="Anime">Anime</option>
        </select>
        
        <select name="min_rating">
            <option value="1">1+ Stars</option>
            <option value="2">2+ Stars</option>
            <option value="3">3+ Stars</option>
            <option value="4">4+ Stars</option>
            <option value="5">5 Stars Only</option>
        </select>

        <button type="submit">Search</button>
        <a href="browse_reviews.php" class="btn">Reset</a> <!-- Reset Button -->
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
        <p>No reviews found matching your search.</p>
    <?php endif; ?>

</body>
</html>

