<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$msg_type = '';

// Handle Image Upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_pic'])) {
    $file = $_FILES['profile_pic'];
    
    // Check for errors
    if ($file['error'] === 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
        $max_size = 2 * 1024 * 1024; // 2MB
        
        if (in_array($file['type'], $allowed_types)) {
            if ($file['size'] <= $max_size) {
                // Generate a unique name
                $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                $new_filename = uniqid('profile_') . '.' . $ext;
                $upload_path = 'uploads/profiles/' . $new_filename;
                
                if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                    // Update Database
                    $stmt = $conn->prepare("UPDATE users SET profile_pic = ? WHERE id = ?");
                    $stmt->bind_param("si", $new_filename, $user_id);
                    $stmt->execute();
                    $stmt->close();
                    
                    $message = "Profile picture updated successfully!";
                    $msg_type = "success";
                } else {
                    $message = "Failed to upload file.";
                    $msg_type = "error";
                }
            } else {
                $message = "File is too large! Maximum allowed is 2MB.";
                $msg_type = "error";
            }
        } else {
            $message = "Invalid file type. Only JPG, PNG, and WEBP are allowed.";
            $msg_type = "error";
        }
    } else {
         $message = "Please select an image first.";
         $msg_type = "error";
    }
}

// Get user data
$stmt = $conn->prepare("SELECT username, email, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_data = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - VibeNet</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .profile-container {
            background: var(--glass-bg);
            padding: 40px;
            border-radius: 15px;
            border: 1px solid var(--glass-border);
            text-align: center;
        }
        .profile-pic-large {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
            margin-bottom: 20px;
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.4);
        }
        .upload-form {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
        }
        .custom-file-upload {
            border: 1px solid var(--primary);
            display: inline-block;
            padding: 8px 15px;
            cursor: pointer;
            border-radius: 8px;
            background: rgba(139, 92, 246, 0.2);
            color: white;
            transition: all 0.3s;
        }
        .custom-file-upload:hover {
            background: var(--primary);
        }
        input[type="file"] {
            display: none;
        }
        .btn-update {
            background: var(--accent);
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1rem;
        }
    </style>
</head>
<body style="display: block; overflow-y: auto;">
    <div class="circle circle-1" style="position: fixed;"></div>
    <div class="circle circle-2" style="position: fixed;"></div>

    <nav class="dashboard-nav">
        <div class="logo">✨ VibeNet</div>
        <div class="user-profile">
            <a href="dashboard.php" style="color: white; text-decoration: none; margin-right: 15px; font-weight: bold;">← Back to Feed</a>
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div style="display: flex; justify-content: center; margin-top: 120px;">
        <div class="dashboard-content" style="margin-top: 0;">
            
            <div class="profile-container">
                <h2>Your Profile</h2>
                
                <?php if($message): ?>
                    <div style="padding: 10px; margin: 15px 0; border-radius: 5px; background: <?= $msg_type == 'success' ? 'rgba(34, 197, 94, 0.2)' : 'rgba(239, 68, 68, 0.2)' ?>; color: <?= $msg_type == 'success' ? '#86efac' : '#fca5a5' ?>;">
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <?php 
                    $pic_url = ($user_data['profile_pic'] !== 'default.png') ? 'uploads/profiles/' . htmlspecialchars($user_data['profile_pic']) : 'https://ui-avatars.com/api/?name='.urlencode($user_data['username']).'&background=8b5cf6&color=fff&size=200';
                ?>
                <img src="<?= $pic_url ?>" alt="Profile Picture" class="profile-pic-large">
                
                <h3 style="color: white; font-size: 1.5rem;"><?= htmlspecialchars($user_data['username']) ?></h3>
                <p style="color: var(--text-muted);"><?= htmlspecialchars($user_data['email']) ?></p>

                <form class="upload-form" method="POST" enctype="multipart/form-data">
                    <label class="custom-file-upload">
                        <input type="file" name="profile_pic" accept="image/jpeg, image/png, image/webp" required id="fileInput">
                        📸 Choose New Picture
                    </label>
                    <div id="fileName" style="font-size: 0.8rem; color: var(--text-muted);"></div>
                    <button type="submit" class="btn-update">Upload & Save</button>
                </form>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('fileInput').addEventListener('change', function() {
            document.getElementById('fileName').textContent = this.files[0] ? this.files[0].name : '';
        });
    </script>
</body>
</html>
