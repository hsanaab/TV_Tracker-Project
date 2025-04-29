<?php
session_start();
include 'db_connect.php';

$error = ""; // Default: no error

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_username = trim($_POST['new_username']);
    $new_email = trim($_POST['new_email']);
    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($new_username) || empty($new_email) || empty($new_password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        // Check if username or email already exists
        $stmt = $conn->prepare("SELECT User_ID FROM Users WHERE Username = ? OR Email = ?");
        $stmt->bind_param("ss", $new_username, $new_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $error = "Username or Email already exists. <a href='login.php'>Go to Login</a>";
        } else {
            // Hash password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

            // Create new user
            $stmt = $conn->prepare("INSERT INTO Users (Username, Email, Password) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sss", $new_username, $new_email, $hashed_password);
                if ($stmt->execute()) {
                    $_SESSION['user_id'] = $stmt->insert_id;
                    header("Location: index.php");
                    exit;
                } else {
                    $error = "Error creating user: " . $stmt->error;
                }
            } else {
                $error = "Error preparing user creation.";
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
    <title>Create New User - TV Tracker</title>
    <link rel="stylesheet" href="styles.css?v=1">
</head>
<body>

    <h1>Create a New User</h1>

    <form method="POST" action="new_user.php">
        <label>Choose a Username:</label><br>
        <input type="text" name="new_username" required><br><br>

        <label>Enter Email:</label><br>
        <input type="email" name="new_email" required><br><br>

        <label>Choose a Password:</label><br>
        <input type="password" name="new_password" required><br><br>

        <label>Confirm Password:</label><br>
        <input type="password" name="confirm_password" required><br><br>

        <?php if (!empty($error)): ?>
            <p style="color: red;"><?php echo $error; ?></p>
        <?php endif; ?>

        <button type="submit">Create Account</button>
    </form>

    <br>
    <a href="login.php">Back to Login</a>

</body>
</html>

