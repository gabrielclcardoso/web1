<?php
header('Content-Type: application/json');
require_once '../srcs/includes/init.php';
require_once '../srcs/utils/Like.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Please login to like']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$imageId = isset($input['imageId']) ? (int)$input['imageId'] : 0;

if ($imageId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Invalid Post ID']);
    exit;
}

$likeHandler = new Like();
$result = $likeHandler->toggle($_SESSION['user_id'], $imageId);
$newCount = $likeHandler->getCount($imageId);

echo json_encode([
    'success' => true,
    'action' => $result['action'],
    'newCount' => $newCount
]);
?>
