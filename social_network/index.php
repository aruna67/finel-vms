<?php
require_once 'db.php';
$error = '';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $error = "Please enter email and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, username, password_hash FROM users WHERE email = ? OR username = ?");
        $stmt->bind_param("ss", $email, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Securely verify password
            if (password_verify($password, $user['password_hash'])) {
                // Regenerate session ID to prevent session fixation attacks
                session_regenerate_id(true);

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];

                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid credentials.";
            }
        } else {
            $error = "Invalid credentials.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - VibeNet</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="circle circle-1"></div>
    <div class="circle circle-2"></div>

    <div class="auth-container">
        <div class="brand">
            <div class="logo">✨ VibeNet</div>
            <p>Welcome back to the vibe.</p>
        </div>

        <form class="auth-form" method="POST" action="">
            <h2>Log In</h2>

            <?php if ($error): ?>
                <div class="alert alert-error"><?= $error ?></div>
            <?php endif; ?>

            <div class="input-group">
                <input type="text" name="email" required placeholder=" "
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
                <label>Email or Username</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" required placeholder=" ">
                <label>Password</label>
            </div>

            <button type="submit" class="btn-primary">Log In</button>
            <p class="switch-link">Don't have an account? <a href="register.php">Sign Up</a></p>
        </form>
    </div>
</body>

</html>