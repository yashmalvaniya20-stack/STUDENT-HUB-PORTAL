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
('23IT003', 'Kabir Varma', '23it003@charusat.edu.in', '9876543203', 'Information Technology', '2nd Year'),
('23CE004', 'Ananya Desai', '23ce004@charusat.edu.in', '9876543204', 'Computer Engineering', '2nd Year'),
('23CE005', 'Rohan Mehta', '23ce005@charusat.edu.in', '9876543205', 'Computer Engineering', '2nd Year'),
('22IT006', 'Priya Sharma', '22it006@charusat.edu.in', '9876543206', 'Information Technology', '3rd Year'),
('22IT007', 'Devansh Trivedi', '22it007@charusat.edu.in', '9876543207', 'Information Technology', '3rd Year'),
('22CE008', 'Ishita Dave', '22ce008@charusat.edu.in', '9876543208', 'Computer Engineering', '3rd Year'),
('24CS009', 'Karan Joshi', '24cs009@charusat.edu.in', '9876543209', 'Computer Science', '1st Year'),
('24CS010', 'Tanvi Parikh', '24cs010@charusat.edu.in', '9876543210', 'Computer Science', '1st Year'),
('24AI011', 'Manav Rao', '24ai011@charusat.edu.in', '9876543211', 'Artificial Intelligence', '1st Year'),
('24AI012', 'Khushi Soni', '24ai012@charusat.edu.in', '9876543212', 'Artificial Intelligence', '1st Year'),
('21IT013', 'Yash Panchal', '21it013@charusat.edu.in', '9876543213', 'Information Technology', '4th Year'),
('21CE014', 'Riddhi Bhatt', '21ce014@charusat.edu.in', '9876543214', 'Computer Engineering', '4th Year'),
('23CS015', 'Sahil Solanki', '23cs015@charusat.edu.in', '9876543215', 'Computer Science', '2nd Year'),
('23CS016', 'Neha Prajapati', '23cs016@charusat.edu.in', '9876543216', 'Computer Science', '2nd Year'),
('22AI017', 'Vivek Chauhan', '22ai017@charusat.edu.in', '9876543217', 'Artificial Intelligence', '3rd Year'),
('22AI018', 'Pooja Solanki', '22ai018@charusat.edu.in', '9876543218', 'Artificial Intelligence', '3rd Year'),
('24IT019', 'Harshil Makwana', '24it019@charusat.edu.in', '9876543219', 'Information Technology', '1st Year'),
('24CE020', 'Vidhi Raval', '24ce020@charusat.edu.in', '9876543220', 'Computer Engineering', '1st Year')
ON DUPLICATE KEY UPDATE name=VALUES(name);