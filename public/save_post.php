<?php
header('Content-Type: application/json');
require_once '../srcs/includes/init.php';
require_once '../srcs/utils/Image.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Acess denied']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['image']) || empty($input['overlay'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Incomplete data']);
    exit;
}

// Decode image to binary
$imgBase64 = preg_replace('#^data:image/\w+;base64,#i', '', $input['image']);
$imgBinary = base64_decode($imgBase64);

if (!$imgBinary) {
    echo json_encode(['success' => false, 'message' => 'Error decoding image']);
    exit;
}

// @ used to suppress warnings
$sourceImg = @imagecreatefromstring($imgBinary);

if (!$sourceImg) {
    echo json_encode(['success' => false, 'message' => 'Invalid image received.']);
    exit;
}

$overlayRelativePath = $input['overlay'];
if (strpos($overlayRelativePath, 'assets/overlays/') !== 0 || strpos($overlayRelativePath, '..') !== false) {
     echo json_encode(['success' => false, 'message' => 'Invalid overlay path']);
     imagedestroy($sourceImg);
     exit;
}

$overlayAbsolutePath = __DIR__ . '/' . $overlayRelativePath;

if (!file_exists($overlayAbsolutePath)) {
    echo json_encode(['success' => false, 'message' => 'Overlay not found']);
    imagedestroy($sourceImg);
    exit;
}

$overlayImg = imagecreatefrompng($overlayAbsolutePath);

// Guarantee transparency
imagealphablending($sourceImg, true);
imagesavealpha($sourceImg, true);

$srcWidth = imagesx($sourceImg);
$srcHeight = imagesy($sourceImg);

$ovrWidth = imagesx($overlayImg);
$ovrHeight = imagesy($overlayImg);

imagecopyresampled(
    $sourceImg,
    $overlayImg,
    0, 0,
    0, 0,
    $srcWidth,
    $srcHeight,
    $ovrWidth,
    $ovrHeight
);

$uploadDir = __DIR__ . '/assets/uploads/';
$fileName = 'post_' . $_SESSION['user_id'] . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.png';
$savePath = $uploadDir . $fileName;

if (!imagepng($sourceImg, $savePath)) {
    echo json_encode(['success' => false, 'message' => 'Unable to save image']);
    imagedestroy($sourceImg);
    imagedestroy($overlayImg);
    exit;
}

$post = new Image();
$dbPath = 'assets/uploads/' . $fileName;

if ($post->create($_SESSION['user_id'], $dbPath)) {
    echo json_encode(['success' => true]);
} else {
    if (file_exists($savePath)) unlink($savePath);
    echo json_encode(['success' => false, 'message' => 'Error storing picture on DB.']);
}

imagedestroy($sourceImg);
if (isset($overlayImg)) imagedestroy($overlayImg);
?>
