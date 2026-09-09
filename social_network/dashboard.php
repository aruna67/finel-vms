<?php
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

// Handle new post submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_post'])) {
    $content = trim(htmlspecialchars($_POST['content']));
    $image_name = NULL;

    // Handle Image Upload
    if (isset($_FILES['post_image']) && $_FILES['post_image']['error'] === 0) {
        $file = $_FILES['post_image'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
        $max_size = 5 * 1024 * 1024; // 5MB limit for posts

        if (in_array($file['type'], $allowed_types) && $file['size'] <= $max_size) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $image_name = uniqid('post_') . '.' . $ext;
            move_uploaded_file($file['tmp_name'], 'uploads/posts/' . $image_name);
        }
    }

    if (!empty($content) || $image_name) {
        $stmt = $conn->prepare("INSERT INTO posts (user_id, content, image_path) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $_SESSION['user_id'], $content, $image_name);
        $stmt->execute();
        $stmt->close();
        header("Location: dashboard.php"); // Refresh
        exit();
    }
}

// Handle new comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
    $post_id = (int)$_POST['post_id'];
    $content = trim(htmlspecialchars($_POST['comment_content']));
    if (!empty($content)) {
        $stmt = $conn->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $post_id, $_SESSION['user_id'], $content);
        $stmt->execute();
        $stmt->close();
        header("Location: dashboard.php"); // Refresh
        exit();
    }
}

// Fetch posts
$posts_query = "SELECT p.*, u.username, u.profile_pic FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC";
$posts_result = $conn->query($posts_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feed - VibeNet</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .post-box { background: var(--glass-bg); padding: 20px; border-radius: 10px; margin-bottom: 20px; border: 1px solid var(--glass-border); }
        .post-box textarea { width: 100%; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); color: white; padding: 15px; border-radius: 8px; margin-bottom: 10px; resize: none; }
        .post-btn { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; float: right; font-weight: bold; }
        .feed-post { background: rgba(15, 23, 42, 0.8); padding: 20px; border-radius: 10px; margin-bottom: 20px; border: 1px solid var(--glass-border); }
        .post-header { display: flex; align-items: center; gap: 10px; margin-bottom: 15px; }
        .profile-thumb { width: 40px; height: 40px; border-radius: 50%; background: var(--accent); display: flex; justify-content: center; align-items: center; font-weight: bold; color: white; }
        .post-content { line-height: 1.6; margin-bottom: 15px; }
        .comments-section { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 15px; margin-top: 15px; }
        .comment { background: rgba(0,0,0,0.2); padding: 10px; border-radius: 8px; margin-bottom: 10px; font-size: 0.9rem; }
        .comment-form { display: flex; gap: 10px; margin-top: 10px; }
        .comment-form input { flex: 1; padding: 8px; background: rgba(0,0,0,0.3); border: 1px solid var(--glass-border); color: white; border-radius: 4px; }
        .comment-form button { background: var(--accent); color: white; border: none; padding: 8px 15px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body style="display: block; overflow-y: auto;">
    <div class="circle circle-1" style="position: fixed;"></div>
    <div class="circle circle-2" style="position: fixed;"></div>

    <?php 
        // Get current user's profile pic for navbar
        $stmt_nav = $conn->prepare("SELECT profile_pic FROM users WHERE id = ?");
        $stmt_nav->bind_param("i", $_SESSION['user_id']);
        $stmt_nav->execute();
        $nav_user = $stmt_nav->get_result()->fetch_assoc();
        $nav_pic = ($nav_user['profile_pic'] !== 'default.png') ? 'uploads/profiles/' . htmlspecialchars($nav_user['profile_pic']) : 'https://ui-avatars.com/api/?name='.urlencode($_SESSION['username']).'&background=8b5cf6&color=fff';
    ?>
    <nav class="dashboard-nav">
        <div class="logo">✨ VibeNet</div>
        <div class="user-profile">
            <a href="profile.php" style="text-decoration: none; display: flex; align-items: center; gap: 10px; color: white;">
                <img src="<?= $nav_pic ?>" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--primary);">
                <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>
            </a>
            <a href="logout.php" class="btn-logout" style="margin-left: 15px;">Logout</a>
        </div>
    </nav>

    <div style="display: flex; justify-content: center;">
        <div class="dashboard-content">
            
            <!-- Create Post Box -->
            <div class="post-box">
                <form method="POST" action="" enctype="multipart/form-data">
                    <textarea name="content" rows="3" placeholder="What's your vibe today?"></textarea>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <input type="file" name="post_image" accept="image/*" id="postImage" style="font-size: 0.8rem; color: #aaa;">
                        <button type="submit" name="submit_post" class="post-btn">Share</button>
                    </div>
                </form>
            </div>

            <!-- Feed -->
            <h2 style="margin-bottom: 20px;">Recent Vibes</h2>
            
            <?php while($post = $posts_result->fetch_assoc()): ?>
                <div class="feed-post">
                    <div class="post-header">
                        <?php 
                            $post_pic = ($post['profile_pic'] !== 'default.png') ? 'uploads/profiles/' . htmlspecialchars($post['profile_pic']) : 'https://ui-avatars.com/api/?name='.urlencode($post['username']).'&background=ec4899&color=fff';
                        ?>
                        <img src="<?= $post_pic ?>" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover; border: 1px solid rgba(255,255,255,0.2);" alt="Thumb">
                        <div>
                            <strong><?= htmlspecialchars($post['username']) ?></strong>
                            <div style="font-size: 0.8rem; color: var(--text-muted);"><?= date('M j, Y g:i A', strtotime($post['created_at'])) ?></div>
                        </div>
                    </div>
                    <div class="post-content">
                        <?= nl2br(htmlspecialchars($post['content'])) ?>
                    </div>
                    
                    <?php if($post['image_path']): ?>
                        <div style="margin-bottom: 15px;">
                            <img src="uploads/posts/<?= htmlspecialchars($post['image_path']) ?>" style="width: 100%; border-radius: 10px; border: 1px solid var(--glass-border);" alt="Post Image">
                        </div>
                    <?php endif; ?>
                    
                    <!-- Comments Section -->
                    <div class="comments-section">
                        <?php
                            $stmt_comments = $conn->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE c.post_id = ? ORDER BY c.created_at ASC");
                            $stmt_comments->bind_param("i", $post['id']);
                            $stmt_comments->execute();
                            $comments_res = $stmt_comments->get_result();
                            while($comment = $comments_res->fetch_assoc()):
                        ?>
                            <div class="comment">
                                <strong><?= htmlspecialchars($comment['username']) ?>:</strong> 
                                <?= htmlspecialchars($comment['content']) ?>
                            </div>
                        <?php 
                            endwhile; 
                            $stmt_comments->close();
                        ?>
                        
                        <!-- Add Comment Form -->
                        <form class="comment-form" method="POST" action="">
                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                            <input type="text" name="comment_content" placeholder="Write a comment..." required>
                            <button type="submit" name="submit_comment">Send</button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
            
        </div>
    </div>
</body>
</html>
