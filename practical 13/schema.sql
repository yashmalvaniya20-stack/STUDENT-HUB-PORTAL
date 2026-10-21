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

INSERT INTO students (student_code, name, email, mobile, course, year) VALUES
('23IT001', 'Aarav Patel', '23it001@charusat.edu.in', '9876543201', 'Information Technology', '2nd Year'),
('23IT002', 'Diya Shah', '23it002@charusat.edu.in', '9876543202', 'Information Technology', '2nd Year'),
('23CE005', 'Rohan Mehta', '23ce005@charusat.edu.in', '9876543205', 'Computer Engineering', '2nd Year'),
('24CS010', 'Tanvi Parikh', '24cs010@charusat.edu.in', '9876543210', 'Computer Science', '1st Year'),
('22AI017', 'Vivek Chauhan', '22ai017@charusat.edu.in', '9876543217', 'Artificial Intelligence', '3rd Year')
ON DUPLICATE KEY UPDATE name=VALUES(name);