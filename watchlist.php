<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include 'db_connect.php';

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Watchlist</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>
<body>

    <h1>View Watchlist</h1>

    <!-- Choose Option -->
    <form method="GET" action="watchlist.php">
        <button type="submit" name="view" value="mine">📋 View My Watchlist</button>
        <button type="submit" name="view" value="search">🔎 Search Another User</button>
    </form>

    <br>

    <?php
    if (isset($_GET['view'])) {
        $view = $_GET['view'];

        if ($view === 'mine') {
            // View my own watchlist
            $sql = "SELECT TS.Title, UW.Status, UW.Date_Added
                    FROM User_Watchlist UW
                    JOIN TV_Shows TS ON UW.Show_ID = TS.Show_ID
                    WHERE UW.User_ID = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "<h2>My Watchlist</h2>";
                echo "<table>
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date Added</th>
                        </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['Title']) . "</td>
                            <td>" . htmlspecialchars($row['Status']) . "</td>
                            <td>" . htmlspecialchars($row['Date_Added']) . "</td>
                          </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No shows found in your watchlist.</p>";
            }
            $stmt->close();

        } elseif ($view === 'search') {
            // Show search form
            ?>
            <form method="GET" action="watchlist.php">
                <input type="hidden" name="view" value="search">
                <input type="text" name="username" placeholder="Enter Username" required>
                <button type="submit">Search</button>
            </form>
            <?php

            if (isset($_GET['username'])) {
                $username = trim($_GET['username']);

                $sql = "SELECT TS.Title, UW.Status, UW.Date_Added
                        FROM User_Watchlist UW
                        JOIN TV_Shows TS ON UW.Show_ID = TS.Show_ID
                        JOIN Users U ON UW.User_ID = U.User_ID
                        WHERE U.Username = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $username);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    echo "<h2>Watchlist for " . htmlspecialchars($username) . "</h2>";
                    echo "<table>
                            <tr>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Date Added</th>
                            </tr>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['Title']) . "</td>
                                <td>" . htmlspecialchars($row['Status']) . "</td>
                                <td>" . htmlspecialchars($row['Date_Added']) . "</td>
                              </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<p>No shows found for user: <strong>" . htmlspecialchars($username) . "</strong></p>";
                }
                $stmt->close();
            }
        }
    }

    $conn->close();
    ?>

    <br>
    <a href="index.php">🏠 Back to Home</a>

</body>
</html>

