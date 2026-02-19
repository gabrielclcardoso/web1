<?php
header('Content-Type: application/json');

require_once '../srcs/includes/init.php';
require_once '../srcs/utils/Image.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Acess Denied']);
    exit;
}

try {
    $imageHandler = new Image();
    $images = $imageHandler->getUserImages($_SESSION['user_id']);
    
    echo json_encode(['success' => true, 'images' => $images]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Error fetching images']);
}
?>
