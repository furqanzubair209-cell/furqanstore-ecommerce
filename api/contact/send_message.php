<?php
// ============================================
// API: api/contact/send_message.php
// ============================================
require_once '../../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$name = $data['name'] ?? '';
$email = $data['email'] ?? '';
$subject = $data['subject'] ?? '';
$message = $data['message'] ?? '';

if (empty($name) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'message' => 'Name, email and message required']);
    exit();
}

$conn = getConnection();
$stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $subject, $message);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Message sent successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to send message']);
}
?>
