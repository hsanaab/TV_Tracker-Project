<?php
session_start();
include 'db_connect.php';

$error = ""; // Default: no error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $username = trim($_POST['username']);

        if (empty($username)) {
            $error = "Username cannot be empty.";
        } else {
            $stmt = $conn->prepare("SELECT User_ID FROM Users WHERE Username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                // User exists, log them in
                $row = $result->fetch_assoc();
                $_SESSION['user_id'] = $row['User_ID'];
                $stmt->close();
                $conn->close();
                header("Location: index.php");
                exit;
            } else {
                // Username not found
                $error = "Username not found. Please try again or create a new user.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TV Tracker</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>
<body>

    <h1>Login to TV Tracker</h1>

    <form method="POST" action="login.php">
        <label>Enter Your Username:</label><br>
        <input type="text" name="username" required>
        <br>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <br>
        <button type="submit" name="login" value="login">Login</button>
    </form>

    <br>
    <a href="new_user.php" class="btn">➕ Create New User</a>

</body>
</html>

