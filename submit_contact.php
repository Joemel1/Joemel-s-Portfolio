<?php
// Database config
$host = 'localhost';
$dbname = 'portfolio_db';
$user = 'root'; // default XAMPP user
$pass = '';     // default no password for XAMPP

// Connect to database using PDO for safety
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation and sanitization
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email address.";
        exit;
    }

    if (empty($message)) {
        echo "Message cannot be empty.";
        exit;
    }

    // Insert into database
    $stmt = $pdo->prepare("INSERT INTO messages (email, message) VALUES (:email, :message)");
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':message', $message);

    if ($stmt->execute()) {
        echo "Thank you! Your message has been saved.";
    } else {
        echo "Error saving your message. Please try again.";
    }
} else {
    echo "Invalid request.";
}
