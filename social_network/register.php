<?php
require_once 'db.php';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim(htmlspecialchars($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($username) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters.";
    } else {
        // Check if email or username exists
        $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
        $stmt_check->bind_param("ss", $email, $username);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $error = "Username or Email already taken.";
        } else {
            // Hash the password securely
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            
            $stmt_insert = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
            $stmt_insert->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt_insert->execute()) {
                $success = "Account created successfully! You can now login.";
            } else {
                $error = "Something went wrong. Please try again.";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join VibeNet</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>
    
    <div class="auth-container">
        <div class="brand">
            <div class="logo">✨ VibeNet</div>
            <p>Connect with the universe.</p>
        </div>
        
        <form class="auth-form" method="POST" action="">
            <h2>Create an Account</h2>
            
            <?php if($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>
            
            <?php if($success): ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <div class="input-group">
                <input type="text" name="username" required placeholder=" ">
                <label>Username</label>
            </div>
            
            <div class="input-group">
                <input type="email" name="email" required placeholder=" ">
                <label>Email Address</label>
            </div>
            
            <div class="input-group">
                <input type="password" name="password" required placeholder=" ">
                <label>Password</label>
            </div>
            
            <div class="input-group">
                <input type="password" name="confirm_password" required placeholder=" ">
                <label>Confirm Password</label>
            </div>
            
            <button type="submit" class="btn-primary">Sign Up</button>
            <p class="switch-link">Already have an account? <a href="index.php">Log In</a></p>
        </form>
    </div>
</body>
</html>
