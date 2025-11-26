<?php
/**
 * Temporary Test File - Debug Post Update
 * Access: /test_post_update.php?id=POST_ID
 * DELETE THIS FILE AFTER DEBUGGING!
 */

// Load CodeIgniter
require_once '../app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

// Create app instance
$app = Config\Services::createRequest(new Config\App());

// Database connection
$db = \Config\Database::connect();

// Get post ID from query
$postId = $_GET['id'] ?? null;

if (!$postId) {
    die('Usage: /test_post_update.php?id=POST_ID');
}

// Get post data
$post = $db->table('posts')->where('id', $postId)->get()->getRow();

if (!$post) {
    die('Post not found with ID: ' . $postId);
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Post Update Debug - ID: <?= $postId ?></title>
    <style>
        body { font-family: monospace; padding: 20px; background: #f5f5f5; }
        .section { background: white; padding: 20px; margin: 10px 0; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .field { margin: 10px 0; padding: 10px; background: #f8f9fa; border-left: 3px solid #007bff; }
        .field strong { display: inline-block; width: 200px; color: #666; }
        .field code { background: #e9ecef; padding: 2px 6px; border-radius: 3px; color: #d63384; }
        .null { color: #999; font-style: italic; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>🔍 Post Update Debug Tool</h1>
    
    <div class="section">
        <h2>Current Post Data (ID: <?= $postId ?>)</h2>
        
        <div class="field">
            <strong>Title:</strong>
            <code><?= htmlspecialchars($post->title) ?></code>
        </div>
        
        <div class="field">
            <strong>Slug:</strong>
            <code><?= htmlspecialchars($post->slug) ?></code>
        </div>
        
        <div class="field">
            <strong>Post Type:</strong>
            <code class="<?= $post->post_type == 'video' ? 'success' : '' ?>">
                <?= htmlspecialchars($post->post_type) ?>
            </code>
        </div>
        
        <div class="field">
            <strong>Video URL:</strong>
            <?php if ($post->video_url): ?>
                <code class="success"><?= htmlspecialchars($post->video_url) ?></code>
            <?php else: ?>
                <span class="null">NULL</span>
            <?php endif; ?>
        </div>
        
        <div class="field">
            <strong>Status:</strong>
            <code><?= htmlspecialchars($post->status) ?></code>
        </div>
        
        <div class="field">
            <strong>Created At:</strong>
            <code><?= $post->created_at ?></code>
        </div>
        
        <div class="field">
            <strong>Updated At:</strong>
            <code><?= $post->updated_at ?></code>
        </div>
    </div>
    
    <div class="section">
        <h2>Test Update Video URL</h2>
        <form method="POST" action="">
            <p>
                <label for="test_video_url">New Video URL:</label><br>
                <input type="text" id="test_video_url" name="test_video_url" 
                       value="<?= htmlspecialchars($post->video_url ?? '') ?>" 
                       style="width: 100%; padding: 8px; margin: 5px 0;">
            </p>
            <button type="submit" style="padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Update Video URL
            </button>
        </form>
        
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_video_url'])) {
            $newVideoUrl = $_POST['test_video_url'];
            
            $result = $db->table('posts')
                ->where('id', $postId)
                ->update([
                    'video_url' => $newVideoUrl,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            
            if ($result) {
                echo '<div style="margin-top: 15px; padding: 15px; background: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; color: #155724;">';
                echo '✅ <strong>Update SUCCESS!</strong> Refresh page to see new value.';
                echo '</div>';
                
                // Redirect to refresh
                echo '<script>setTimeout(() => window.location.reload(), 2000);</script>';
            } else {
                echo '<div style="margin-top: 15px; padding: 15px; background: #f8d7da; border: 1px solid #f5c6cb; border-radius: 4px; color: #721c24;">';
                echo '❌ <strong>Update FAILED!</strong> Check database permissions.';
                echo '</div>';
            }
        }
        ?>
    </div>
    
    <div class="section">
        <h2>📋 Instructions</h2>
        <ol>
            <li>Check if current video_url is saved correctly</li>
            <li>Try updating via this form to test direct database update</li>
            <li>If this form works, the issue is in the CodeIgniter controller/model</li>
            <li><strong style="color: red;">DELETE THIS FILE after debugging!</strong></li>
        </ol>
    </div>
    
    <div class="section">
        <h2>🔗 Links</h2>
        <ul>
            <li><a href="/dashboard/posts/edit/<?= $postId ?>">Edit Post in Admin</a></li>
            <li><a href="/dashboard/posts">Back to Posts List</a></li>
            <li><a href="?id=<?= $postId ?>">Refresh This Page</a></li>
        </ul>
    </div>
</body>
</html>
