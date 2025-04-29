<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$search_term = '';
$shows = [];
$error_message = '';

if (isset($_GET['error'])) {
    if ($_GET['error'] === 'duplicate') {
        $error_message = 'You already rated this show.';
    } elseif ($_GET['error'] === 'invalid') {
        $error_message = 'Invalid input. Please check your form.';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $search_term = trim($_POST['search'] ?? '');

    if (!empty($search_term)) {
        $stmt = $conn->prepare("SELECT Show_ID, Title, Release_Year FROM TV_Shows WHERE Title LIKE ? ORDER BY Title");
        $like_search = "%$search_term%";
        $stmt->bind_param("s", $like_search);
        $stmt->execute();
        $result = $stmt->get_result();
        $shows = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rate / Watch Existing Show</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>
<body>

<h1>⭐ Rate or Watch a Show</h1>

<div class="navigation">
    <a href="index.php" class="btn">🏠 Back to Home</a>
    <a href="add_show.php" class="btn">➕ Add New Show</a>
</div>

<?php if (!empty($error_message)): ?>
    <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form action="rate_show.php" method="POST">
    <label>Search for a Show:
        <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
    </label>
    <button type="submit">Search</button>
</form>

<?php if (!empty($shows)): ?>
    <form action="insert_rating.php" method="POST">
        <label>Select a Show:
            <select name="show_id" required>
                <?php foreach ($shows as $show): ?>
                    <option value="<?php echo $show['Show_ID']; ?>">
                        <?php echo htmlspecialchars($show['Title'] . " (" . $show['Release_Year'] . ")"); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>Rating (1-5):
            <input type="number" name="rating" min="1" max="5" required>
        </label>

        <label>Comment:
            <textarea name="comment" required></textarea>
        </label>

        <label>Status:
            <select name="status" required>
                <option value="watched">Watched</option>
                <option value="watching">Watching</option>
                <option value="to watch">To Watch</option>
            </select>
        </label>

        <button type="submit">Submit Rating / Watchlist</button>
    </form>
<?php elseif ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
    <p class="error">No shows found matching your search.</p>
<?php endif; ?>

</body>
</html>

