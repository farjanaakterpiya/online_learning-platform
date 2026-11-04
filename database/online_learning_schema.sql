-- Online Learning Platform
-- Database Schema: 15 tables (based on the project proposal)
-- Database: online_learning
-- MySQL 8.0+ / MariaDB | InnoDB | utf8mb4

CREATE DATABASE IF NOT EXISTS online_learning
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE online_learning;

-- 1. Users: Admin, Instructor and Student
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(30),
    profile_image VARCHAR(255),
    role ENUM('admin','instructor','student') NOT NULL DEFAULT 'student',
    status ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. Course categories
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    slug VARCHAR(150) NOT NULL UNIQUE,
    description TEXT,
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 3. Courses
CREATE TABLE courses (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    instructor_id BIGINT UNSIGNED NOT NULL,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    short_description VARCHAR(500),
    description LONGTEXT,
    thumbnail VARCHAR(255),
    level ENUM('beginner','intermediate','advanced') DEFAULT 'beginner',
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    language VARCHAR(60) DEFAULT 'English',
    status ENUM('draft','published','archived') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_courses_instructor FOREIGN KEY (instructor_id)
        REFERENCES users(id) ON DELETE RESTRICT,
    CONSTRAINT fk_courses_category FOREIGN KEY (category_id)
        REFERENCES categories(id) ON DELETE RESTRICT,
    INDEX idx_courses_status_category (status, category_id)
) ENGINE=InnoDB;

-- 4. Course sections/modules
CREATE TABLE course_sections (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_sections_course FOREIGN KEY (course_id)
        REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_sections_course_order (course_id, sort_order)
) ENGINE=InnoDB;

-- 5. Lessons
CREATE TABLE lessons (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    section_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    lesson_type ENUM('video','article','file','link') DEFAULT 'video',
    content LONGTEXT,
    resource_url VARCHAR(500),
    duration_minutes INT UNSIGNED DEFAULT 0,
    is_preview BOOLEAN DEFAULT FALSE,
    sort_order INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_lessons_section FOREIGN KEY (section_id)
        REFERENCES course_sections(id) ON DELETE CASCADE,
    INDEX idx_lessons_section_order (section_id, sort_order)
) ENGINE=InnoDB;

-- 6. Student course enrollments
CREATE TABLE enrollments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending','active','completed','cancelled') DEFAULT 'pending',
    progress_percent DECIMAL(5,2) DEFAULT 0.00,
    completed_at DATETIME,
    CONSTRAINT fk_enrollments_student FOREIGN KEY (student_id)
        REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_enrollments_course FOREIGN KEY (course_id)
        REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY uq_student_course (student_id, course_id),
    INDEX idx_enrollments_course_status (course_id, status)
) ENGINE=InnoDB;

-- 7. Payment records
CREATE TABLE payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id BIGINT UNSIGNED NOT NULL,
    transaction_ref VARCHAR(120) UNIQUE,
    amount DECIMAL(10,2) NOT NULL,
    currency CHAR(3) DEFAULT 'BDT',
    payment_method VARCHAR(60),
    payment_status ENUM('pending','paid','failed','refunded') DEFAULT 'pending',
    paid_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payments_enrollment FOREIGN KEY (enrollment_id)
        REFERENCES enrollments(id) ON DELETE CASCADE,
    INDEX idx_payments_status (payment_status)
) ENGINE=InnoDB;

-- 8. Quizzes
CREATE TABLE quizzes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    pass_mark DECIMAL(5,2) DEFAULT 50.00,
    time_limit_minutes INT UNSIGNED,
    status ENUM('draft','published') DEFAULT 'draft',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_quizzes_course FOREIGN KEY (course_id)
        REFERENCES courses(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. Quiz questions
CREATE TABLE questions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quiz_id BIGINT UNSIGNED NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('mcq','true_false','short_answer') DEFAULT 'mcq',
    marks DECIMAL(6,2) DEFAULT 1.00,
    sort_order INT UNSIGNED DEFAULT 0,
    CONSTRAINT fk_questions_quiz FOREIGN KEY (quiz_id)
        REFERENCES quizzes(id) ON DELETE CASCADE,
    INDEX idx_questions_quiz_order (quiz_id, sort_order)
) ENGINE=InnoDB;

-- 10. Options for quiz questions
CREATE TABLE quiz_options (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    question_id BIGINT UNSIGNED NOT NULL,
    option_text TEXT NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    sort_order INT UNSIGNED DEFAULT 0,
    CONSTRAINT fk_quiz_options_question FOREIGN KEY (question_id)
        REFERENCES questions(id) ON DELETE CASCADE,
    INDEX idx_quiz_options_question (question_id)
) ENGINE=InnoDB;

-- 11. Student quiz attempts/results
CREATE TABLE quiz_attempts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    quiz_id BIGINT UNSIGNED NOT NULL,
    student_id BIGINT UNSIGNED NOT NULL,
    started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    submitted_at DATETIME,
    score DECIMAL(7,2) DEFAULT 0.00,
    total_marks DECIMAL(7,2) DEFAULT 0.00,
    status ENUM('in_progress','submitted','graded') DEFAULT 'in_progress',
    CONSTRAINT fk_attempts_quiz FOREIGN KEY (quiz_id)
        REFERENCES quizzes(id) ON DELETE CASCADE,
    CONSTRAINT fk_attempts_student FOREIGN KEY (student_id)
        REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_attempts_student_quiz (student_id, quiz_id)
) ENGINE=InnoDB;

-- 12. Course completion certificates
CREATE TABLE certificates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id BIGINT UNSIGNED NOT NULL UNIQUE,
    certificate_code VARCHAR(100) NOT NULL UNIQUE,
    issued_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    certificate_file VARCHAR(255),
    CONSTRAINT fk_certificates_enrollment FOREIGN KEY (enrollment_id)
        REFERENCES enrollments(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 13. Live classes
CREATE TABLE live_classes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    course_id BIGINT UNSIGNED NOT NULL,
    instructor_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(200) NOT NULL,
    meeting_url VARCHAR(500) NOT NULL,
    starts_at DATETIME NOT NULL,
    ends_at DATETIME,
    status ENUM('scheduled','live','completed','cancelled') DEFAULT 'scheduled',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_live_classes_course FOREIGN KEY (course_id)
        REFERENCES courses(id) ON DELETE CASCADE,
    CONSTRAINT fk_live_classes_instructor FOREIGN KEY (instructor_id)
        REFERENCES users(id) ON DELETE RESTRICT,
    INDEX idx_live_classes_course_start (course_id, starts_at)
) ENGINE=InnoDB;

-- 14. Course reviews and ratings
CREATE TABLE course_reviews (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment_id BIGINT UNSIGNED NOT NULL UNIQUE,
    rating TINYINT UNSIGNED NOT NULL,
    review_text TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_enrollment FOREIGN KEY (enrollment_id)
        REFERENCES enrollments(id) ON DELETE CASCADE,
    CONSTRAINT chk_course_review_rating CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB;

-- 15. Student wishlists
CREATE TABLE wishlists (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id BIGINT UNSIGNED NOT NULL,
    course_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_wishlists_student FOREIGN KEY (student_id)
        REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_wishlists_course FOREIGN KEY (course_id)
        REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY uq_wishlist_student_course (student_id, course_id)
) ENGINE=InnoDB;
