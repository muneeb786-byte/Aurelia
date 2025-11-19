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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $room_category = trim($_POST['room_category'] ?? ''); // optional now
    $check_in_date = trim($_POST['checkin_date'] ?? '');
    $check_out_date = trim($_POST['checkout_date'] ?? '');
    $guests = trim($_POST['guests'] ?? '');
    $special_requests = trim($_POST['special_requests'] ?? '');

    // Validation
    $errors = [];
    if (empty($full_name)) $errors[] = 'Full name is required.';
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
    if (empty($phone)) $errors[] = 'Phone is required.';
    // room_category optional, so no validation
    if (empty($check_in_date)) $errors[] = 'Check-in date is required.';
    if (empty($check_out_date)) $errors[] = 'Check-out date is required.';
    if (empty($guests)) $errors[] = 'Number of guests is required.';

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO room_bookings (full_name, email, phone, room_category, check_in_date, check_out_date, guests, special_requests) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$full_name, $email, $phone, $room_category, $check_in_date, $check_out_date, $guests, $special_requests])) {
            // Redirect to homepage
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
