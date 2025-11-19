<?php
// Database connection
$host = 'localhost';
$dbname = 'hotel_management';
$username = 'root'; // Replace with your MySQL username
$password = ''; // Replace with your MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize data
    $name = trim($_POST['name'] ?? ($_POST['full_name'] ?? ''));
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? null); // Optional for first contact form
    $subject = trim($_POST['subject'] ?? null); // Optional for first contact form
    $message = trim($_POST['message'] ?? '');

    // Validation
    $errors = [];
    if (empty($name)) $errors[] = 'Name is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (empty($message)) $errors[] = 'Message is required.';
    if (!empty($phone) && !preg_match('/^[0-9+\-\s()]+$/', $phone)) $errors[] = 'Invalid phone number.';

    if (empty($errors)) {
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $subject, $message])) {
            // Redirect to homepage
            header("Location: index.html"); // <-- اپنا homepage file name یہاں لکھو
            exit(); // یہ لازمی ہے تاکہ code execution بند ہو جائے
        } else {
            echo "Error sending message.";
        }
    } else {
        echo "Errors: " . implode(', ', $errors);
    }
} else {
    echo "Invalid request.";
}
