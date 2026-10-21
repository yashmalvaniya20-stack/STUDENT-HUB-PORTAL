CREATE DATABASE IF NOT EXISTS studenthub;
USE studenthub;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS students (
    student_id INT AUTO_INCREMENT PRIMARY KEY,
    student_code VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(20) NOT NULL,
    course VARCHAR(50) NOT NULL,
    year VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    venue VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    status ENUM('upcoming', 'completed', 'cancelled') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS registrations (
    registration_id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    event_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(20) DEFAULT 'confirmed',
    FOREIGN KEY (student_id) REFERENCES students(student_id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES events(event_id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS audit_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    username VARCHAR(50) DEFAULT 'Guest',
    action VARCHAR(100) NOT NULL,
    details TEXT,
    ip_address VARCHAR(45) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (username, name, email, password, role) VALUES
('admin', 'Chief Administrator', 'admin@charusat.edu.in', '$2y$10$UKbcXuaLwobsv2mSUycxFOT/xCbQzuxAYNFBiybyxgC6DY7dN922.', 'admin'),
('student', 'Demo Student User', 'student@charusat.edu.in', '$2y$10$UKbcXuaLwobsv2mSUycxFOT/xCbQzuxAYNFBiybyxgC6DY7dN922.', 'student')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO students (student_code, name, email, mobile, course, year) VALUES
('23IT001', 'Aarav Patel', '23it001@charusat.edu.in', '9876543201', 'Information Technology', '2nd Year'),
('23IT002', 'Diya Shah', '23it002@charusat.edu.in', '9876543202', 'Information Technology', '2nd Year'),
('23CE005', 'Rohan Mehta', '23ce005@charusat.edu.in', '9876543205', 'Computer Engineering', '2nd Year'),
('24CS010', 'Tanvi Parikh', '24cs010@charusat.edu.in', '9876543210', 'Computer Science', '1st Year')
ON DUPLICATE KEY UPDATE name=VALUES(name);

INSERT INTO events (title, description, event_date, venue, category, status) VALUES
('Tech Euphoria 2026', 'Annual departmental tech fest and project showcase.', '2026-11-15', 'Central Auditorium', 'Technical', 'upcoming'),
('Web Craft Hackathon', '24-hour coding challenge.', '2026-11-20', 'Lab 301, Depstar', 'Coding', 'upcoming'),
('AI & Computer Vision Workshop', 'Interactive masterclass on computer vision.', '2026-12-05', 'Seminar Hall 1', 'Workshop', 'upcoming')
ON DUPLICATE KEY UPDATE title=VALUES(title);

INSERT INTO registrations (student_id, event_id, status) VALUES
(1, 1, 'confirmed'),
(2, 1, 'confirmed'),
(3, 2, 'confirmed')
ON DUPLICATE KEY UPDATE status=VALUES(status);

INSERT INTO audit_logs (user_id, username, action, details, ip_address) VALUES
(1, 'admin', 'SYSTEM_INIT', 'StudentHub administrative database initialized successfully.', '127.0.0.1');