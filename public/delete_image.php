<?php
header('Content-Type: application/json');
require_once '../srcs/includes/init.php';
require_once '../srcs/utils/Image.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['imageId'])) {
    echo json_encode(['success' => false, 'message' => 'Missing Image ID']);
    exit;
}

$imageHandler = new Image();
if ($imageHandler->delete($input['imageId'], $_SESSION['user_id'])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to delete']);
}
