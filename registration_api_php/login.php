<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);

    $email = $requestData['email'];
    $password = $requestData['password'];

    require_once 'Database.php';

    // Replace the following with your actual database connection logic
    $db = new Database();

    $conn = $db->getConnection();

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
		$user = $result->fetch_assoc();
		$hashedPassword = $user['password'];
		if (password_verify($password, $hashedPassword)) {
			echo json_encode(['success' => true, 'message' => 'Login successful']);
		}else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
        
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }

    $conn->close();
} else {
    header('Location: index.html');
    exit;
}

?>
