<?php
require_once '../srcs/includes/init.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = "Camagru - Studio";
require_once '../srcs/includes/header.php';
?>

<div class="editor-container">
    <div class="camera-section">
		<div class="camera-wrapper">
			<video id="video" autoplay playsinline></video>
        	<canvas id="canvas" style="display:none;"></canvas>
			<img id="overlay-preview" class="overlay-preview-img">
		</div>

		<div class="camera-controls">
			<button id="capture-btn" class="btn">Take picture</button>
			<button id="save-btn" class="btn btn-success">Save Picture</button>
			<button id="clear-btn" class="btn btn-danger">Discard & Retake</button>
		</div>
    </div>

	<div class="upload-section">
            <p style="margin-bottom: 10px; font-size: 0.9em;">Or upload a picture:</p>
            <input type="file" id="file-upload" accept="image/png, image/jpeg, image/jpg" style="margin-bottom: 10px;">
    </div>

    <div class="sidebar">
        <h3>Choose an overlay</h3>
        <div class="overlay-selection">
            <img src="assets/overlays/santa_hat.png" class="overlay-item" data-id="1">
            <img src="assets/overlays/beach.png" class="overlay-item" data-id="2">
        </div>
		<div id="gallery-preview"></div>
    </div>
</div>


<script src="js/camera.js"></script>

<?php require_once '../srcs/includes/footer.php'; ?>
