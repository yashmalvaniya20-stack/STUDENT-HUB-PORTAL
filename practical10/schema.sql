CREATE DATABASE IF NOT EXISTS studenthub;
USE studenthub;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') DEFAULT 'student',
    remember_token VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, name, email, password, role) VALUES
('admin', 'Administrator User', 'admin@charusat.edu.in', '$2y$10$UKbcXuaLwobsv2mSUycxFOT/xCbQzuxAYNFBiybyxgC6DY7dN922.', 'admin'),
('student', 'Demo Student', 'student@charusat.edu.in', '$2y$10$UKbcXuaLwobsv2mSUycxFOT/xCbQzuxAYNFBiybyxgC6DY7dN922.', 'student')
ON DUPLICATE KEY UPDATE name=VALUES(name);