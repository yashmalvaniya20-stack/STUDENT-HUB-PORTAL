CREATE DATABASE IF NOT EXISTS studenthub;
USE studenthub;

CREATE TABLE IF NOT EXISTS events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    event_date DATE NOT NULL,
    venue VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    status ENUM('upcoming', 'completed', 'cancelled') DEFAULT 'upcoming',
    poster VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO events (title, description, event_date, venue, category, status, poster) VALUES
('Tech Euphoria 2026', 'Annual departmental tech fest with coding contests, robotics and project display.', '2026-11-15', 'Central Auditorium', 'Technical', 'upcoming', NULL),
('Web Craft Hackathon', 'Build innovative full stack web apps in 24 hours with peer developers.', '2026-11-20', 'Lab 301, Depstar', 'Coding', 'upcoming', NULL),
('AI & Computer Vision Workshop', 'Interactive masterclass on computer vision and neural networks.', '2026-12-05', 'Seminar Hall 1', 'Workshop', 'upcoming', NULL)
ON DUPLICATE KEY UPDATE title=VALUES(title);