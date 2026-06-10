CREATE DATABASE IF NOT EXISTS goodness_platform
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE goodness_platform;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS chat_logs;
DROP TABLE IF EXISTS course_materials;
DROP TABLE IF EXISTS faq_entries;
DROP TABLE IF EXISTS messages;
DROP TABLE IF EXISTS announcements;
DROP TABLE IF EXISTS course_enrollments;
DROP TABLE IF EXISTS courses;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(120) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('admin', 'lecturer', 'student') NOT NULL,
    matric_number VARCHAR(50) NULL,
    department VARCHAR(120) NULL,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE courses (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) NOT NULL UNIQUE,
    title VARCHAR(160) NOT NULL,
    lecturer_id INT UNSIGNED NULL,
    description TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_courses_lecturer
        FOREIGN KEY (lecturer_id) REFERENCES users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE course_enrollments (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_student_course (student_id, course_id),
    CONSTRAINT fk_enrollments_student
        FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_enrollments_course
        FOREIGN KEY (course_id) REFERENCES courses(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE announcements (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    author_id INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NULL,
    title VARCHAR(180) NOT NULL,
    body TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_announcements_author
        FOREIGN KEY (author_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_announcements_course
        FOREIGN KEY (course_id) REFERENCES courses(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE messages (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    lecturer_id INT UNSIGNED NOT NULL,
    course_id INT UNSIGNED NULL,
    subject VARCHAR(180) NOT NULL,
    body TEXT NOT NULL,
    reply TEXT NULL,
    status ENUM('open', 'answered') NOT NULL DEFAULT 'open',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    replied_at TIMESTAMP NULL,
    CONSTRAINT fk_messages_student
        FOREIGN KEY (student_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_messages_lecturer
        FOREIGN KEY (lecturer_id) REFERENCES users(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_messages_course
        FOREIGN KEY (course_id) REFERENCES courses(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE faq_entries (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(80) NOT NULL DEFAULT 'General',
    question VARCHAR(220) NOT NULL,
    keywords VARCHAR(255) NOT NULL,
    answer TEXT NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE course_materials (
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

CREATE TABLE chat_logs (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    source ENUM('faq', 'material', 'smalltalk', 'fallback', 'ai', 'validation') NOT NULL DEFAULT 'faq',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_chat_logs_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE SET NULL
) ENGINE=InnoDB;
