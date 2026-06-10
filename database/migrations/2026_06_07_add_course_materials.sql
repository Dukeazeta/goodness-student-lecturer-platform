USE goodness_platform;

CREATE TABLE IF NOT EXISTS course_materials (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id INT UNSIGNED NOT NULL,
    uploader_id INT UNSIGNED NOT NULL,
    title VARCHAR(180) NOT NULL,
    description TEXT NULL,
    searchable_text MEDIUMTEXT NOT NULL,
    file_path VARCHAR(255) NULL,
    file_name VARCHAR(180) NULL,
    file_type VARCHAR(20) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_materials_course
        FOREIGN KEY (course_id) REFERENCES courses(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_materials_uploader
        FOREIGN KEY (uploader_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

ALTER TABLE chat_logs
    MODIFY source ENUM('faq', 'material', 'fallback', 'ai', 'validation') NOT NULL DEFAULT 'faq';

INSERT INTO course_materials (course_id, uploader_id, title, description, searchable_text, is_active)
SELECT 1, 2, 'Web Application Development Project Notes', 'Core notes for CSC421 project implementation',
       'A database-driven web application should include authentication, role-based access, prepared SQL statements, input validation, and clear user workflows. In this project, students communicate with lecturers through messages, lecturers post announcements, and administrators manage users, courses, FAQ entries, and course materials. PHP handles server-side logic while MySQL stores users, courses, enrollments, messages, announcements, FAQ entries, materials, and chat logs.',
       1
WHERE NOT EXISTS (SELECT 1 FROM course_materials WHERE title = 'Web Application Development Project Notes');

INSERT INTO course_materials (course_id, uploader_id, title, description, searchable_text, is_active)
SELECT 2, 4, 'Computer Networks Communication Summary', 'Overview material for CSC423',
       'Computer networks allow devices to exchange data using protocols and addressing. A web-based communication platform depends on client-server communication where the browser sends HTTP requests to the server and receives responses. Reliable communication requires correct routing, error handling, and secure handling of user data. Networked learning systems help distribute announcements, messages, and academic materials to students.',
       1
WHERE NOT EXISTS (SELECT 1 FROM course_materials WHERE title = 'Computer Networks Communication Summary');

INSERT INTO course_materials (course_id, uploader_id, title, description, searchable_text, is_active)
SELECT 3, 2, 'Chatbot Support Material', 'Introductory material for chatbot behavior',
       'A chatbot is a computer program that simulates conversation with users. In an academic support system, the chatbot can answer frequently asked questions and search course materials for relevant answers. The chatbot should provide short source-backed answers, avoid guessing when no strong match exists, and log unknown questions so administrators can improve the knowledge base.',
       1
WHERE NOT EXISTS (SELECT 1 FROM course_materials WHERE title = 'Chatbot Support Material');
