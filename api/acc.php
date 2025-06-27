<?php
require_once 'config.php'; // Your DB connection

// Admin user details
$username = 'Jordan';
$name = 'Jordan Wheeler';
$plainPassword = 'Nadroj09!';

// Secure password hash
$hashedPassword = password_hash($plainPassword, PASSWORD_DEFAULT);

// Prepare SQL insert
$stmt = $conn->prepare("INSERT INTO users (username, name, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $name, $hashedPassword);

// Execute and respond
if ($stmt->execute()) {
    echo "Admin user created successfully.";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
