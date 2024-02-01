<?php

class User
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function register($name, $email, $phone, $password, $file)
    {
		// Validate inputs
        if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($file)) {
            return ['success' => false, 'message' => 'All fields are required'];
        }
		
		// Sanitize inputs
        $name = filter_var($name, FILTER_SANITIZE_STRING);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        $phone = filter_var($phone, FILTER_SANITIZE_STRING);
        $password = filter_var($password, FILTER_SANITIZE_STRING);
		$sanitizedFileName  = filter_var(basename($file['name']), FILTER_SANITIZE_STRING);
		
        // Process file upload
        $uploadPath = 'uploads/' . $sanitizedFileName;
        if(!move_uploaded_file($file['tmp_name'], $uploadPath)){
			return ['success' => false, 'message' => 'File upload Failed', 'error' => 'Try again to upload new file'];
		}

        // Save user data to the database
        $stmt = $this->db->prepare('INSERT INTO users (name, email, phone, password, file_path) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('sssss', $name, $email, $phone, $password, $uploadPath);

        if ($stmt->execute()) {
            return ['success' => true, 'message' => 'Registration successful'];
        } else {
            return ['success' => false, 'message' => 'Registration failed', 'error' => $stmt->error];
        }
    }
}

?>
