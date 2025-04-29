<?php
session_start();
include 'db_connect.php';

// Handle error messages
$error_message = '';
if (isset($_GET['error']) && $_GET['error'] === 'exists') {
    $error_message = 'This show already exists. Please rate or add it to your watchlist.';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add TV Show</title>
    <link rel="stylesheet" href="styles.css?v=1">
    <script>
        function limitCheckboxes() {
            const checkboxes = document.querySelectorAll('input[name="genres[]"]');
            checkboxes.forEach(box => {
                box.addEventListener('change', () => {
                    const checkedCount = document.querySelectorAll('input[name="genres[]"]:checked').length;
                    if (checkedCount >= 3) {
                        checkboxes.forEach(c => {
                            if (!c.checked) c.disabled = true;
                        });
                    } else {
                        checkboxes.forEach(c => c.disabled = false);
                    }
                });
            });
        }
        window.addEventListener('DOMContentLoaded', limitCheckboxes);
    </script>
</head>
<body>

<h1>➕ Add a New TV Show</h1>

<div class="navigation">
    <a href="index.php" class="btn">🏠 Back to Home</a>
    <a href="rate_show.php" class="btn">⭐ Rate or Add to Watchlist</a>
</div>

<?php if (!empty($error_message)): ?>
    <p class="error"><?php echo htmlspecialchars($error_message); ?></p>
<?php endif; ?>

<form action="insert_show.php" method="POST">
    <label>Title:
        <input type="text" name="title" required>
    </label>

    <label>Release Year:
        <input type="number" name="release_year" required>
    </label>

    <label>Synopsis:
        <textarea name="synopsis" required></textarea>
    </label>

    <div>
        <p><strong>Select up to 3 genres:</strong></p>
        <?php
        $genre_query = "SELECT Genre_ID, Genre_Name FROM Genres ORDER BY Genre_Name";
        $genre_result = $conn->query($genre_query);
        while ($genre = $genre_result->fetch_assoc()) {
            echo "<label><input type='checkbox' name='genres[]' value='{$genre['Genre_ID']}'> " . htmlspecialchars($genre['Genre_Name']) . "</label><br>";
        }
        ?>
    </div>

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

    <button type="submit">Add Show</button>
</form>

</body>
</html>

