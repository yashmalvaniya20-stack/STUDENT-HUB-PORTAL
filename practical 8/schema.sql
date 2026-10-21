CREATE DATABASE IF NOT EXISTS studenthub;
USE studenthub;

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

INSERT INTO students (student_code, name, email, mobile, course, year) VALUES
('23IT001', 'Aarav Patel', '23it001@charusat.edu.in', '9876543210', 'Information Technology', '2nd Year'),
('23IT002', 'Diya Shah', '23it002@charusat.edu.in', '9876543211', 'Information Technology', '2nd Year'),
('23CE015', 'Rohan Mehta', '23ce015@charusat.edu.in', '9876543212', 'Computer Engineering', '2nd Year'),
('22IT045', 'Priya Sharma', '22it045@charusat.edu.in', '9876543213', 'Information Technology', '3rd Year'),
('24CS010', 'Karan Joshi', '24cs010@charusat.edu.in', '9876543214', 'Computer Science', '1st Year');

INSERT INTO events (title, description, event_date, venue, category, status) VALUES
('Tech Euphoria 2026', 'Annual Departmental Technical Symposium and Hackathon', '2026-11-15', 'Central Auditorium', 'Technical', 'upcoming'),
('Web Craft Hackathon', '24-hour full stack web development competition', '2026-11-20', 'Lab 301, Depstar', 'Coding', 'upcoming'),
('AI & Robotics Workshop', 'Hands-on practical session on computer vision', '2026-12-05', 'Seminar Hall 1', 'Workshop', 'upcoming'),
('Alumni Connect 2026', 'Networking session with industry alumni', '2026-10-10', 'Main Amphitheatre', 'Networking', 'completed');

INSERT INTO registrations (student_id, event_id, status) VALUES
(1, 1, 'confirmed'),
(2, 1, 'confirmed'),
(3, 2, 'confirmed'),
(4, 3, 'confirmed'),
(5, 1, 'confirmed');