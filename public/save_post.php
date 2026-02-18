<?php
ini_set('display_errors', 0);
header('Content-Type: application/json');

require_once '../srcs/includes/init.php';
require_once '../srcs/utils/Image.php';
require_once '../srcs/utils/ImageProcessor.php'; 

try {
    // Initial Validations
    if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Access denied.');
    }

    $input = json_decode(file_get_contents('php://input'), true);
    if (!$input || empty($input['image']) || empty($input['overlay'])) {
        throw new Exception('Incomplete data.');
    }

    $overlayRelative = $input['overlay'];
    if (strpos($overlayRelative, 'assets/overlays/') !== 0 || strpos($overlayRelative, '..') !== false) {
        throw new Exception('Invalid overlay path');
    }

    $overlayAbsolutePath = __DIR__ . '/' . $overlayRelative;
    $uploadDir = __DIR__ . '/assets/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    
    $fileName = 'post_' . $_SESSION['user_id'] . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.png';
    $savePath = $uploadDir . $fileName;

	// Image processing and saving
    $processor = new ImageProcessor();
    $processor->loadFromBase64($input['image'])
              ->applyOverlay($overlayAbsolutePath)
              ->save($savePath);

	// Database uploading
    $dbPath = 'assets/uploads/' . $fileName;
    $post = new Image(); 
    
    if (!$post->create($_SESSION['user_id'], $dbPath)) {
        if (file_exists($savePath)) unlink($savePath);
        throw new Exception('Error registering image on the database');
    }

    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>
