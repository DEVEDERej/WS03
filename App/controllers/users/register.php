<?php

use Framework\Database;

// GET request - Show registration form
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    loadView('users/register');
    exit;
}

// POST request - Process registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    $data = [
        'name' => trim($_POST['name'] ?? ''),
        'email' => trim($_POST['email'] ?? ''),
        'city' => trim($_POST['city'] ?? ''),
        'state' => trim($_POST['state'] ?? ''),
        'password' => $_POST['password'] ?? '',
        'password_confirmation' => $_POST['password_confirmation'] ?? ''
    ];

    // Validation
    if (empty($data['name'])) {
        $errors[] = 'Name is required';
    }

    if (empty($data['email'])) {
        $errors[] = 'Email is required';
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format';
    }

    if (empty($data['city'])) {
        $errors[] = 'City is required';
    }

    if (empty($data['state'])) {
        $errors[] = 'State is required';
    }

    if (empty($data['password'])) {
        $errors[] = 'Password is required';
    } elseif (strlen($data['password']) < 6) {
        $errors[] = 'Password must be at least 6 characters';
    }

    if ($data['password'] !== $data['password_confirmation']) {
        $errors[] = 'Passwords do not match';
    }

    // If there are errors, show the form again with errors
    if (!empty($errors)) {
        loadView('users/register', [
            'errors' => $errors,
            'data' => $data
        ]);
        exit;
    }

    // Try to insert into database
    try {
        $config = require basePath('config/db.php');
        $db = new Database($config);

        // Check if email already exists
        $existingUser = $db->Query("SELECT id FROM users WHERE email = '{$data['email']}'")->fetch();
        
        if ($existingUser) {
            $errors[] = 'Email already registered';
            loadView('users/register', [
                'errors' => $errors,
                'data' => $data
            ]);
            exit;
        }

        // Hash the password
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        // Insert user into database
        $query = "INSERT INTO users (name, email, city, state, password, created_at) 
                  VALUES ('{$data['name']}', '{$data['email']}', '{$data['city']}', '{$data['state']}', '{$hashedPassword}', NOW())";
        
        $db->Query($query);

        // Success! Show success message
        loadView('users/register', [
            'success' => 'Registration successful! You can now login.',
            'data' => []
        ]);

    } catch (Exception $e) {
        $errors[] = 'Database error: ' . $e->getMessage();
        loadView('users/register', [
            'errors' => $errors,
            'data' => $data
        ]);
    }
}
