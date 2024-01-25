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
        // Process file upload
        $uploadPath = 'uploads/' . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $uploadPath);

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
