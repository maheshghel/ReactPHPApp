<?php

header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once 'User.php';

	if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['phone']) && isset($_POST['password']) && isset($_POST['confirmPassword']) && isset($_FILES['file'])){
		
	
		// Sanitize inputs
        $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
        $confirmPassword = filter_var($_POST['confirmPassword'], FILTER_SANITIZE_STRING);
		
		$file = $_FILES['file'];

		// Validate input
		$validationErrors = validateInput($name, $email, $phone, $password, $confirmPassword, $file);

		if (!empty($validationErrors)) {
			echo json_encode(['success' => false, 'message' => 'Validation failed', 'errors' => $validationErrors]);
			exit;
		}

		// Hash the password
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		// Save user data to the database
		require_once 'Database.php';
		$db = new Database();

		$user = new User($db->getConnection());

		$registrationResult = $user->register($name, $email, $phone, $hashedPassword, $file);

		echo json_encode($registrationResult);
	}else{
		echo json_encode(['success' => false, 'message' => 'Check all fields', 'errors' => 'Empty fields']);
		exit;
	}    
} else {
    header('Location: index.html');
    exit;
}

function validateInput($name, $email, $phone, $password, $confirmPassword, $file)
{
    $errors = [];

    if (empty($name)) {
        $errors['name'] = 'Name is required';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format';
    }

    if (empty($phone)) {
        $errors['phone'] = 'Phone is required';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required';
    }

    if ($password !== $confirmPassword) {
        $errors['confirmPassword'] = 'Passwords do not match';
    }

    // File type validation
    $allowedExtensions = ['jpg', 'jpeg', 'png'];
    $uploadedExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($uploadedExtension, $allowedExtensions)) {
        $errors['file'] = 'Invalid file type. Allowed types are: ' . implode(', ', $allowedExtensions);
    }

    // File size validation
    $maxFileSize = 2 * 1024 * 1024; // 2 MB
    if ($file['size'] > $maxFileSize) {
        $errors['file'] = 'File size exceeds the maximum allowed size';
    }

    // File MIME type validation
    $allowedMimeTypes = ['image/jpeg', 'image/png'];
    $uploadedMimeType = mime_content_type($file['tmp_name']);
    if (!in_array($uploadedMimeType, $allowedMimeTypes)) {
        $errors['file'] = 'Invalid file MIME type. Allowed types are: ' . implode(', ', $allowedMimeTypes);
    }

    return $errors;
}

?>
