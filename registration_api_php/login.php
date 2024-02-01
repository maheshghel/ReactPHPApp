<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);
	
	$email = filter_var($requestData['email'], FILTER_SANITIZE_EMAIL);
    $password = filter_var($requestData['password'], FILTER_SANITIZE_STRING);	
	
	// Validate inputs
	if (empty($email) || empty($password)) {
		echo json_encode(['success' => false, 'message' => 'Empty Request']);
		exit;
	}

    require_once 'Database.php';
    $db = new Database();

    $conn = $db->getConnection();

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
		$user = $result->fetch_assoc();
		$hashedPassword = $user['password'];
		if (password_verify($password, $hashedPassword)) {
			//generating token
			$token = generateToken($user['id']);
			
			echo json_encode(['success' => true, 'message' => 'Login successful', 'token' => $token]);
		}else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
        
    } else {
        echo json_encode(['success' => false, 'message' => 'User not found']);
    }

	$stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Empty Request']);
    exit;
}

function generateToken($userId) {
	$config = require_once 'config/config.php';
    $secretKey = $config['secret_key'];
    $payload = [
        'user_id' => $userId,
        'exp' => time() + 3600, // Token expiration time (1 hour)
    ];
	$payloadString = json_encode($payload);
    return base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT'])) . '.' . base64_encode(json_encode($payload)) . '.' . hash_hmac('sha256', $payloadString, $secretKey);
}

?>
