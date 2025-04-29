<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TV Tracker - Home</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>
<body>

    <h1>Welcome to TV Tracker 📺</h1>

    <div class="navigation">
        <a href="add_show.php" class="btn">➕ Add a New Show</a>
        <a href="browse_reviews.php" class="btn">🔎 Browse Reviews</a>
        <a href="watchlist.php" class="btn">📋 View Watchlist</a>
        <a href="logout.php" class="btn">🚪 Logout</a>
    </div>

    <br><hr><br>

    <h2>All TV Shows</h2>

    <table>
        <tr>
            <th>Title</th>
            <th>Release Year</th>
            <th>Synopsis</th>
        </tr>

        <?php
        $sql = "SELECT * FROM TV_Shows";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row['Title']) . "</td>
                        <td>" . htmlspecialchars($row['Release_Year']) . "</td>
                        <td>" . htmlspecialchars($row['Synopsis']) . "</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No TV Shows Found</td></tr>";
        }
        $conn->close();
        ?>
    </table>

</body>
</html>

