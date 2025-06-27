<?php
require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$title = $_POST['title'] ?? '';
$category = $_POST['category'] ?? '';
$author = $_POST['author'] ?? '';
$tags = $_POST['tags'] ?? '';
$excerpt = $_POST['excerpt'] ?? '';
$content = $_POST['content'] ?? '';

// Validation
if (empty($title) || empty($category) || empty($author) || empty($content)) {
    echo json_encode(['error' => 'Required fields missing']);
    exit;
}

// Insert into database
$stmt = $conn->prepare("INSERT INTO articles (title, content, tags, author, category, date_posted, views) VALUES (?, ?, ?, ?, ?, NOW(), 0)");
$stmt->bind_param("sssss", $title, $content, $tags, $author, $category);

if ($stmt->execute()) {
    $articleId = $conn->insert_id;
    echo json_encode(['success' => true, 'articleId' => $articleId]);
} else {
    echo json_encode(['error' => 'Database error']);
}

$stmt->close();
$conn->close();
?>