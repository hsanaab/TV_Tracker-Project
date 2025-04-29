
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add TV Show</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <h1>Add a New TV Show</h1>

    <form action="insert_show.php" method="POST">
        <!-- Title -->
        <label>Title:</label>
        <input type="text" name="title" required>

        <!-- Release Year -->
        <label>Release Year:</label>
        <input type="number" name="release_year" required>

        <!-- Synopsis -->
        <label>Synopsis:</label>
        <textarea name="synopsis" required></textarea>

        <!-- Genres (Checkboxes) -->
        <label>Select up to 3 Genres:</label><br>
        <div id="genre-checkboxes">
            <input type="checkbox" name="genres[]" value="7"> Drama<br>
            <input type="checkbox" name="genres[]" value="5"> Comedy<br>
            <input type="checkbox" name="genres[]" value="21"> Science Fiction<br>
            <input type="checkbox" name="genres[]" value="8"> Fantasy<br>
            <input type="checkbox" name="genres[]" value="6"> Crime Fiction<br>
            <input type="checkbox" name="genres[]" value="12"> Horror<br>
            <input type="checkbox" name="genres[]" value="17"> Romance<br>
            <input type="checkbox" name="genres[]" value="24"> Thriller<br>
            <input type="checkbox" name="genres[]" value="14"> Mystery<br>
            <input type="checkbox" name="genres[]" value="3"> Anime<br>
        </div>
        <small>You can select up to 3 genres.</small><br><br>

        <!-- Rating -->
        <label>Rating (1-5):</label>
        <input type="number" name="rating" min="1" max="5" required>

        <!-- Comment -->
        <label>Your Comment:</label>
        <textarea name="comment" required></textarea>

        <!-- Watchlist Status -->
        <label>Watchlist Status:</label>
        <select name="status" required>
            <option value="watched">Watched</option>
            <option value="watching">Watching</option>
            <option value="to watch">To Watch</option>
        </select>

        <br><br>
        <button type="submit">Add Show</button>
    </form>

    <br>
    <a href="index.php">🏠 Back to Home</a>

    <!-- JavaScript to enforce max 3 checkbox selection -->
    <script>
    const checkboxes = document.querySelectorAll('#genre-checkboxes input[type="checkbox"]');
    checkboxes.forEach(chk => {
        chk.addEventListener('change', function() {
            const checked = document.querySelectorAll('#genre-checkboxes input[type="checkbox"]:checked');
            if (checked.length > 3) {
                this.checked = false;
                alert('You can only select up to 3 genres.');
            }
        });
    });
    </script>

</body>
</html>

