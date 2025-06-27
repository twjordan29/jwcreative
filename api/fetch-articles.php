<?php
require_once 'config.php';

// Set JSON header
header('Content-Type: application/json; charset=utf-8');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Only GET method allowed']);
    exit;
}

// Get query parameters
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$limit = isset($_GET['limit']) ? max(1, min(50, intval($_GET['limit']))) : 9; // Max 50, default 9
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$offset = ($page - 1) * $limit;

// Estimate read time based on word count
function estimateReadTime($content, $wpm = 225) {
    $wordCount = str_word_count(strip_tags($content));
    $minutes = ceil($wordCount / $wpm);
    return "{$minutes} min read";
}

// Build query with optional category filter
$whereClause = "";
$params = [];
$types = "";

if (!empty($category) && $category !== 'All Posts') {
    $whereClause = "WHERE category = ?";
    $params[] = $category;
    $types .= "s";
}

// Get total count for pagination info
$countQuery = "SELECT COUNT(*) as total FROM articles $whereClause";
$countStmt = $conn->prepare($countQuery);

if (!empty($params)) {
    $countStmt->bind_param($types, ...$params);
}

if ($countStmt->execute()) {
    $countResult = $countStmt->get_result();
    $totalArticles = $countResult->fetch_assoc()['total'];
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
    exit;
}

// Get articles with pagination
$query = "SELECT id, title, content, author, tags, category, date_posted, date_updated 
          FROM articles 
          $whereClause 
          ORDER BY date_posted DESC 
          LIMIT ? OFFSET ?";

$stmt = $conn->prepare($query);

// Add pagination parameters
$params[] = $limit;
$params[] = $offset;
$types .= "ii";

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

if ($stmt->execute()) {
    $result = $stmt->get_result();
    $articles = [];

    while ($row = $result->fetch_assoc()) {
        // Sanitize fields
        $sanitized = array_map('htmlspecialchars', $row);

        // Add read time (calculated from content)
        $sanitized['read_time'] = estimateReadTime($row['content']);

        $articles[] = $sanitized;
    }

    // Calculate pagination info
    $totalPages = ceil($totalArticles / $limit);
    $hasMore = $page < $totalPages;

    // Return articles with pagination metadata
    echo json_encode([
        'articles' => $articles,
        'pagination' => [
            'current_page' => $page,
            'total_pages' => $totalPages,
            'total_articles' => $totalArticles,
            'has_more' => $hasMore,
            'per_page' => $limit
        ],
        'category' => $category ?: 'All Posts'
    ]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Database error']);
}

$countStmt->close();
$stmt->close();
$conn->close();
?>