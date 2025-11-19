<?php
// Database connection
$host = 'localhost';
$dbname = 'hotel_management';
$username = 'root'; 
$password = ''; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $date = trim($_POST['date'] ?? '');
    $time = trim($_POST['time'] ?? '');
    $guests = (int)($_POST['guests'] ?? 0);

    // Validation
    $errors = [];
    if (empty($name)) $errors[] = 'Name is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (empty($phone) || !preg_match('/^[0-9+\-\s()]+$/', $phone)) $errors[] = 'Valid phone is required.';
    if (empty($date) || !strtotime($date)) $errors[] = 'Valid date is required.';
    if (empty($time) || !preg_match('/^\d{2}:\d{2}$/', $time)) $errors[] = 'Valid time is required (HH:MM).';
    if ($guests < 1 || $guests > 20) $errors[] = 'Guests must be between 1 and 20.';

    if (empty($errors)) {
        // Insert into database
        $stmt = $pdo->prepare("INSERT INTO table_reservations (name, email, phone, date, time, guests) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$name, $email, $phone, $date, $time, $guests])) {
            // Redirect to homepage after successful reservation
            header("Location: index.html");
            exit();
        } else {
            print_r($stmt->errorInfo());
        }
    } else {
        echo "Errors: " . implode(', ', $errors);
    }
} else {
    echo "Invalid request.";
}
?>
