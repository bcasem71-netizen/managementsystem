<?php
// Database connection settings for local XAMPP/MySQL environments.
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'student_management';

// Create a new MySQLi connection.
$conn = new mysqli($host, $username, $password);

// Stop execution if the connection fails.
if ($conn->connect_error) {
    die('Database connection failed: ' . $conn->connect_error);
}

// Create database if it does not already exist.
$createDbSql = "CREATE DATABASE IF NOT EXISTS `$database`";
if (!$conn->query($createDbSql)) {
    die('Error creating database: ' . $conn->error);
}

// Select the database for further operations.
if (!$conn->select_db($database)) {
    die('Error selecting database: ' . $conn->error);
}

// Create students table if it does not already exist.
$createTableSql = "
    CREATE TABLE IF NOT EXISTS students (
        id INT AUTO_INCREMENT PRIMARY KEY,
        student_name VARCHAR(100) NOT NULL,
        roll_number VARCHAR(50) NOT NULL UNIQUE,
        email VARCHAR(100) NOT NULL UNIQUE,
        weight DECIMAL(6,2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )
";

if (!$conn->query($createTableSql)) {
    die('Error creating table: ' . $conn->error);
}
