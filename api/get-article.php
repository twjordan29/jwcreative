<?php
// Fixed version - navigation within same category
require_once 'config.php';
header('Content-Type: application/json; charset=utf-8');

function estimateReadTime($content, $wpm = 225) {
    $wordCount = str_word_count(strip_tags($content));
    $minutes = ceil($wordCount / $wpm);
    return "{$minutes} min read";
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid article ID']);
    exit;
}

$id = intval($_GET['id']);

// Update views first
$stmt = $conn->prepare("UPDATE articles SET views = views + 1 WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->close();

// Get the current article
$stmt = $conn->prepare("SELECT id, title, content, tags, author, category, date_posted, views FROM articles WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($article = $result->fetch_assoc()) {
        $article['read_time'] = estimateReadTime($article['content']);
        $currentCategory = $article['category'];
        $currentId = $article['id'];
        
        // NAVIGATION WITHIN SAME CATEGORY
        // Get previous article in same category (by ID - older article = lower ID)
        $prevStmt = $conn->prepare("
            SELECT id, title 
            FROM articles 
            WHERE category = ? AND id < ? 
            ORDER BY id DESC 
            LIMIT 1
        ");
        $prevStmt->bind_param("si", $currentCategory, $currentId);
        $prevStmt->execute();
        $prevResult = $prevStmt->get_result();
        $previousArticle = $prevResult->fetch_assoc();
        $prevStmt->close();
        
        // Get next article in same category (by ID - newer article = higher ID)
        $nextStmt = $conn->prepare("
            SELECT id, title 
            FROM articles 
            WHERE category = ? AND id > ? 
            ORDER BY id ASC 
            LIMIT 1
        ");
        $nextStmt->bind_param("si", $currentCategory, $currentId);
        $nextStmt->execute();
        $nextResult = $nextStmt->get_result();
        $nextArticle = $nextResult->fetch_assoc();
        $nextStmt->close();
        
        // If no articles in same category, fall back to any category
        if (!$previousArticle) {
            $prevStmt = $conn->prepare("
                SELECT id, title 
                FROM articles 
                WHERE id < ? 
                ORDER BY id DESC 
                LIMIT 1
            ");
            $prevStmt->bind_param("i", $currentId);
            $prevStmt->execute();
            $prevResult = $prevStmt->get_result();
            $previousArticle = $prevResult->fetch_assoc();
            $prevStmt->close();
        }
        
        if (!$nextArticle) {
            $nextStmt = $conn->prepare("
                SELECT id, title 
                FROM articles 
                WHERE id > ? 
                ORDER BY id ASC 
                LIMIT 1
            ");
            $nextStmt->bind_param("i", $currentId);
            $nextStmt->execute();
            $nextResult = $nextStmt->get_result();
            $nextArticle = $nextResult->fetch_assoc();
            $nextStmt->close();
        }
        
        // Add navigation data to article
        $article['navigation'] = [
            'previous' => $previousArticle ? [
                'id' => $previousArticle['id'],
                'title' => $previousArticle['title']
            ] : null,
            'next' => $nextArticle ? [
                'id' => $nextArticle['id'],
                'title' => $nextArticle['title']
            ] : null
        ];
        
        // Sanitize all fields EXCEPT content (preserve HTML in content)
        $sanitized = [];
        foreach ($article as $key => $value) {
            if ($key === 'content') {
                // Keep content as-is to preserve HTML tags
                $sanitized[$key] = $value;
            } elseif (is_array($value)) {
                $sanitized[$key] = array_map(function($item) {
                    return is_array($item) ? array_map('htmlspecialchars', $item) : htmlspecialchars($item ?? '');
                }, $value);
            } else {
                $sanitized[$key] = htmlspecialchars($value ?? '');
            }
        }
        
        echo json_encode($sanitized);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Article not found']);
    }
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

$stmt->close();
$conn->close();
?>