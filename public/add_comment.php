<?php
header('Content-Type: application/json');
require_once '../srcs/includes/init.php';
require_once '../srcs/utils/Comment.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Login to comment']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$imageId = isset($input['imageId']) ? (int)$input['imageId'] : 0;
$commentText = isset($input['comment']) ? trim($input['comment']) : '';

if ($imageId <= 0 || empty($commentText)) {
    echo json_encode(['success' => false, 'message' => 'Invalid comment.']);
    exit;
}

$commentHandler = new Comment();
if ($commentHandler->add($_SESSION['user_id'], $imageId, $commentText)) {
    echo json_encode([
        'success' => true,
		'username' => htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'),
        'content' => htmlspecialchars($commentText, ENT_QUOTES, 'UTF-8')
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error adding comment.']);
}
