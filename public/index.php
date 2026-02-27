<?php
require_once '../srcs/includes/init.php';
require_once '../srcs/utils/Image.php';

$page_title = 'Camagru - Home';
require_once '../srcs/includes/header.php';

$imagesPerPage = 5;

$currentPage = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;

$offset = ($currentPage - 1) * $imagesPerPage;

$imageHandler = new Image();

$totalPosts = $imageHandler->getTotalCount();
$totalPages = ceil($totalPosts / $imagesPerPage);
$currentUserId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

$images = $imageHandler->getAll($imagesPerPage, $offset, $currentUserId);

$isLoggedIn = isset($_SESSION['user_id']);
?>

<div class="gallery-container">
    <h1>Public Gallery</h1>

    <?php if (empty($images)): ?>
        <div class="card" style="text-align: center; padding: 2rem;">
            <p>No pictures yet. Be the first to post something!</p>
            <?php if ($isLoggedIn): ?>
                <a href="studio.php" class="btn-link">Go to Studio</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="feed">
            <?php foreach ($images as $image): ?>
                <div class="post-card">
                    <div class="post-header">
                        <span class="post-author"><?php echo htmlspecialchars($image['username']); ?></span>
                        <span class="post-date"><?php echo date('d M Y, H:i', strtotime($image['created_at'])); ?></span>
                    </div>

                    <img src="<?php echo htmlspecialchars($image['path']); ?>" alt="Post by <?php echo htmlspecialchars($image['username']); ?>" class="post-image">

                    <div class="post-actions">
                        <?php if ($isLoggedIn): ?>
						<button class="action-btn like-btn" data-id="<?php echo $image['id']; ?>" style="color: <?php echo ($image['user_liked'] > 0) ? 'red' : '#555'; ?>;">
							❤️ <span class="like-count"><?php echo $image['like_count']; ?></span>
						</button>
                            <button class="action-btn comment-btn" onclick="document.getElementById('comment-input-<?php echo $image['id']; ?>').focus();">
                                💬 Comment
                            </button>
                        <?php else: ?>
                            <p style="font-size: 0.9em; color: #777;">Log in to like and comment.</p>
                        <?php endif; ?>
                    </div>

                    <div class="post-comments">
                        <?php if ($isLoggedIn): ?>
                            <div class="add-comment">
                                <input type="text" id="comment-input-<?php echo $image['id']; ?>" placeholder="Add a comment..." class="comment-input">
                                <button class="btn-send-comment" data-id="<?php echo $image['id']; ?>">Send</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
			</div>
				<?php if ($totalPages > 1): ?>
					<div class="pagination">
						<?php if ($currentPage > 1): ?>
							<a href="?page=<?php echo $currentPage - 1; ?>" class="page-link">&laquo; Previous</a>
						<?php endif; ?>
							<span class="page-info">Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?></span>
						<?php if ($currentPage < $totalPages): ?>
							<a href="?page=<?php echo $currentPage + 1; ?>" class="page-link">Next &raquo;</a>
						<?php endif; ?>
					</div>
    		    <?php endif; ?>
    		</div>
        </div>
    <?php endif; ?>
</div>

<script src="js/gallery.js"></script>

<?php 
require_once '../srcs/includes/footer.php'; 
?>
