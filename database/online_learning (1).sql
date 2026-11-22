-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 21, 2026 at 05:43 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `online_learning`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `status`, `created_at`) VALUES
(1, 'abc', 'abc', 'jaljflasjfsk', 'active', '2026-11-21 12:13:39'),
(2, 'Web Development', 'web-development', 'HTML, CSS, JavaScript, PHP and modern frameworks', 'active', '2026-11-21 13:19:27'),
(3, 'Mobile App Development', 'mobile-app-development', 'Build Android and iOS apps', 'active', '2026-11-21 13:19:27'),
(4, 'Data Science', 'data-science', 'Python, statistics, machine learning', 'active', '2026-11-21 13:19:27'),
(5, 'Graphic Design', 'graphic-design', 'Photoshop, Illustrator and branding', 'active', '2026-11-21 13:19:27'),
(6, 'Digital Marketing', 'digital-marketing', 'SEO, social media and ads', 'active', '2026-11-21 13:19:27'),
(7, 'Language Learning', 'language-learning', 'English and other languages', 'active', '2026-11-21 13:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_id` bigint(20) UNSIGNED NOT NULL,
  `certificate_code` varchar(100) NOT NULL,
  `issued_at` datetime DEFAULT current_timestamp(),
  `certificate_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `enrollment_id`, `certificate_code`, `issued_at`, `certificate_file`) VALUES
(1, 1, 'dad', '2026-11-21 18:17:13', 'https://www.bucketlistly.blog/posts/best-free-travel-images'),
(2, 2, 'CERT-2026-0002', '2026-11-18 16:35:00', 'certificates/CERT-2026-0002.pdf'),
(3, 3, 'CERT-2026-0003', '2026-11-17 14:05:00', 'certificates/CERT-2026-0003.pdf'),
(4, 4, 'CERT-2026-0004', '2026-11-19 18:15:00', 'certificates/CERT-2026-0004.pdf'),
(5, 5, 'CERT-2026-0005', '2026-11-20 12:05:00', 'certificates/CERT-2026-0005.pdf'),
(6, 6, 'CERT-2026-0006', '2026-11-20 21:50:00', 'certificates/CERT-2026-0006.pdf'),
(7, 7, 'CERT-2026-0007', '2026-11-21 09:20:00', 'certificates/CERT-2026-0007.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `slug` varchar(220) NOT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `level` enum('beginner','intermediate','advanced') DEFAULT 'beginner',
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `language` varchar(60) DEFAULT 'English',
  `status` enum('draft','published','archived') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `instructor_id`, `category_id`, `title`, `slug`, `short_description`, `description`, `thumbnail`, `level`, `price`, `language`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 'abc', '123', 'skjfks', 'sfksdfjksdjfks', '', 'beginner', 0.00, 'English', 'draft', '2026-11-21 12:14:04', '2026-11-21 12:14:04'),
(2, 3, 2, 'Full Stack Web Development', 'full-stack-web-development', 'Learn HTML, CSS, JavaScript, PHP and MySQL', 'Complete beginner to job-ready web development course with real projects.', 'uploads/courses/web-dev.jpg', 'beginner', 2500.00, 'English', 'published', '2026-11-21 13:19:27', '2026-11-21 13:19:27'),
(3, 4, 3, 'Flutter App Development', 'flutter-app-development', 'Build cross-platform apps with Flutter', 'Learn Dart and Flutter to build Android and iOS apps from scratch.', 'uploads/courses/flutter.jpg', 'intermediate', 1800.00, 'English', 'published', '2026-11-21 13:19:27', '2026-11-21 13:19:27'),
(4, 3, 4, 'Python for Data Science', 'python-for-data-science', 'Pandas, NumPy and data visualization', 'Analyze data and build ML models using Python.', 'uploads/courses/data-science.jpg', 'intermediate', 3000.00, 'English', 'published', '2026-11-21 13:19:27', '2026-11-21 13:19:27'),
(5, 4, 5, 'Photoshop Masterclass', 'photoshop-masterclass', 'Photo editing and graphic design', 'Learn professional photo editing, retouching and poster design.', 'uploads/courses/photoshop.jpg', 'beginner', 1500.00, 'Bangla', 'published', '2026-11-21 13:19:27', '2026-11-21 13:19:27'),
(6, 3, 6, 'Digital Marketing Essentials', 'digital-marketing-essentials', 'SEO, Facebook ads and email marketing', 'Grow a business online with practical marketing strategies.', 'uploads/courses/marketing.jpg', 'beginner', 2200.00, 'Bangla', 'published', '2026-11-21 13:19:27', '2026-11-21 13:19:27'),
(7, 4, 7, 'Spoken English Course', 'spoken-english-course', 'Speak English confidently', 'Daily conversation, grammar and pronunciation practice.', 'uploads/courses/english.jpg', 'beginner', 2000.00, 'Bangla', 'draft', '2026-11-21 13:19:27', '2026-11-21 13:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `course_reviews`
--

CREATE TABLE `course_reviews` (
  `id` int(11) NOT NULL,
  `enrollment_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `review_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_reviews`
--

INSERT INTO `course_reviews` (`id`, `enrollment_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 1, 5, 'sfsd', '2026-11-21 12:31:49'),
(2, 2, 5, 'Excellent course, very well explained and project based.', '2026-11-21 13:19:27'),
(3, 3, 4, 'Good content for beginners.', '2026-11-21 13:19:27'),
(4, 4, 5, 'Loved the Flutter examples. Instructor is very helpful.', '2026-11-21 13:19:27'),
(5, 5, 4, 'Great value for money, would like more practice tasks.', '2026-11-21 13:19:27'),
(6, 6, 5, 'Best data science course I have taken so far.', '2026-11-21 13:19:27'),
(7, 7, 3, 'Nice lessons but some videos could be shorter.', '2026-11-21 13:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `course_sections`
--

CREATE TABLE `course_sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `sort_order` int(10) UNSIGNED DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_sections`
--

INSERT INTO `course_sections` (`id`, `course_id`, `title`, `description`, `sort_order`, `created_at`) VALUES
(1, 1, 'sfsfs', 'sdfsd', 3, '2026-11-21 12:14:29'),
(2, 2, 'Introduction to Web', 'How the web works and setting up tools', 1, '2026-11-21 13:19:27'),
(3, 2, 'HTML and CSS Basics', 'Build your first responsive page', 2, '2026-11-21 13:19:27'),
(4, 3, 'Dart Fundamentals', 'Variables, functions and OOP in Dart', 1, '2026-11-21 13:19:27'),
(5, 4, 'Python and NumPy', 'Python basics and numerical computing', 1, '2026-11-21 13:19:27'),
(6, 5, 'Photoshop Interface', 'Tools, layers and workspace', 1, '2026-11-21 13:19:27'),
(7, 6, 'SEO Fundamentals', 'Keywords, on-page and off-page SEO', 1, '2026-11-21 13:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `enrolled_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('pending','active','completed','cancelled') DEFAULT 'pending',
  `progress_percent` decimal(5,2) DEFAULT 0.00,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `student_id`, `course_id`, `enrolled_at`, `status`, `progress_percent`, `completed_at`) VALUES
(1, 2, 1, '2026-11-21 12:16:36', 'active', 59.99, '2026-11-27 18:16:00'),
(2, 2, 2, '2026-11-10 10:00:00', 'completed', 100.00, '2026-11-18 16:30:00'),
(3, 5, 1, '2026-11-11 11:15:00', 'completed', 100.00, '2026-11-17 14:00:00'),
(4, 5, 3, '2026-11-12 09:40:00', 'completed', 100.00, '2026-11-19 18:10:00'),
(5, 6, 2, '2026-11-13 20:05:00', 'completed', 100.00, '2026-11-20 12:00:00'),
(6, 6, 4, '2026-11-14 08:30:00', 'completed', 100.00, '2026-11-20 21:45:00'),
(7, 7, 5, '2026-11-15 13:20:00', 'completed', 100.00, '2026-11-21 09:15:00'),
(8, 2, 3, '2026-11-21 13:27:35', 'active', 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `lesson_type` enum('video','article','file','link') DEFAULT 'video',
  `content` longtext DEFAULT NULL,
  `resource_url` varchar(500) DEFAULT NULL,
  `duration_minutes` int(10) UNSIGNED DEFAULT 0,
  `is_preview` tinyint(1) DEFAULT 0,
  `sort_order` int(10) UNSIGNED DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `section_id`, `title`, `lesson_type`, `content`, `resource_url`, `duration_minutes`, `is_preview`, `sort_order`, `created_at`) VALUES
(1, 1, 'dsgds', 'article', 'sdgd', 'https://www.bucketlistly.blog/posts/best-free-travel-images', 5, 1, 1, '2026-11-21 12:16:03'),
(2, 2, 'Welcome and Course Overview', 'video', NULL, 'https://www.youtube.com/watch?v=example1', 8, 1, 1, '2026-11-21 13:19:27'),
(3, 3, 'HTML Structure and Tags', 'video', NULL, 'https://www.youtube.com/watch?v=example2', 25, 0, 1, '2026-11-21 13:19:27'),
(4, 4, 'Your First Dart Program', 'video', NULL, 'https://www.youtube.com/watch?v=example3', 18, 1, 1, '2026-11-21 13:19:27'),
(5, 5, 'Installing Python and Jupyter', 'article', 'Step by step guide to install Python, pip and Jupyter Notebook.', NULL, 10, 0, 1, '2026-11-21 13:19:27'),
(6, 6, 'Photoshop Workspace Tour', 'video', NULL, 'https://www.youtube.com/watch?v=example4', 15, 1, 1, '2026-11-21 13:19:27'),
(7, 7, 'What is SEO? (PDF Notes)', 'file', NULL, 'uploads/lessons/seo-notes.pdf', 12, 0, 1, '2026-11-21 13:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `lesson_progress`
--

CREATE TABLE `lesson_progress` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `completed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `enrollment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `live_classes`
--

CREATE TABLE `live_classes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `meeting_url` varchar(500) NOT NULL,
  `starts_at` datetime NOT NULL,
  `ends_at` datetime DEFAULT NULL,
  `status` enum('scheduled','live','completed','cancelled') DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `live_classes`
--

INSERT INTO `live_classes` (`id`, `course_id`, `instructor_id`, `title`, `meeting_url`, `starts_at`, `ends_at`, `status`, `created_at`) VALUES
(1, 1, 3, 'Doubt Clearing Session', 'https://meet.google.com/abc-defg-hij', '2026-11-23 20:00:00', '2026-11-23 21:00:00', 'scheduled', '2026-11-21 13:19:27'),
(2, 2, 3, 'Live Project: Build a Portfolio Site', 'https://meet.google.com/bcd-efgh-ijk', '2026-11-24 19:00:00', '2026-11-24 21:00:00', 'scheduled', '2026-11-21 13:19:27'),
(3, 3, 4, 'Flutter State Management Workshop', 'https://zoom.us/j/1234567890', '2026-11-25 18:30:00', '2026-11-25 20:00:00', 'scheduled', '2026-11-21 13:19:27'),
(4, 4, 3, 'Data Cleaning with Pandas', 'https://meet.google.com/cde-fghi-jkl', '2026-11-20 20:00:00', '2026-11-20 21:30:00', 'completed', '2026-11-21 13:19:27'),
(5, 5, 4, 'Photo Retouching Live Demo', 'https://zoom.us/j/9876543210', '2026-11-26 17:00:00', '2026-11-26 18:30:00', 'scheduled', '2026-11-21 13:19:27'),
(6, 6, 3, 'Facebook Ads Q&A', 'https://meet.google.com/def-ghij-klm', '2026-11-19 21:00:00', '2026-11-19 22:00:00', 'cancelled', '2026-11-21 13:19:27'),
(7, 7, 4, 'Speaking Practice Group Session', 'https://zoom.us/j/5556667778', '2026-11-27 19:30:00', '2026-11-27 20:30:00', 'scheduled', '2026-11-21 13:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `enrollment_id` bigint(20) UNSIGNED NOT NULL,
  `transaction_ref` varchar(120) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` char(3) DEFAULT 'BDT',
  `payment_method` varchar(60) DEFAULT NULL,
  `payment_status` enum('pending','paid','failed','refunded') DEFAULT 'pending',
  `paid_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `enrollment_id`, `transaction_ref`, `amount`, `currency`, `payment_method`, `payment_status`, `paid_at`, `created_at`) VALUES
(1, 1, 'FREE-0001', 0.00, 'BDT', 'free', 'paid', '2026-11-21 12:16:36', '2026-11-21 13:19:27'),
(2, 2, 'BKASH-8F3K2L1A', 2500.00, 'BDT', 'bKash', 'paid', '2026-11-10 10:02:00', '2026-11-21 13:19:27'),
(3, 3, 'FREE-0002', 0.00, 'BDT', 'free', 'paid', '2026-11-11 11:15:00', '2026-11-21 13:19:27'),
(4, 4, 'NAGAD-7Q9X4M2B', 1800.00, 'BDT', 'Nagad', 'paid', '2026-11-12 09:43:00', '2026-11-21 13:19:27'),
(5, 5, 'CARD-5T2R8N6C', 2500.00, 'BDT', 'Card', 'paid', '2026-11-13 20:07:00', '2026-11-21 13:19:27'),
(6, 6, 'BKASH-2H7D5P9E', 3000.00, 'BDT', 'bKash', 'paid', '2026-11-14 08:33:00', '2026-11-21 13:19:27'),
(7, 7, 'NAGAD-9L4W1K3F', 1500.00, 'BDT', 'Nagad', 'paid', '2026-11-15 13:22:00', '2026-11-21 13:19:27'),
(8, 8, 'TXN-CE748BF38017', 1800.00, 'BDT', 'bKash', 'paid', '2026-11-21 19:27:39', '2026-11-21 13:27:39');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('mcq','true_false','short_answer') DEFAULT 'mcq',
  `marks` decimal(6,2) DEFAULT 1.00,
  `sort_order` int(10) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `question_type`, `marks`, `sort_order`) VALUES
(1, 1, 'What does HTML stand for?', 'mcq', 1.00, 1),
(2, 2, 'Which CSS property is used to change the text color?', 'mcq', 1.00, 1),
(3, 3, 'Which programming language is used in Flutter?', 'mcq', 1.00, 1),
(4, 4, 'Which Python library is mostly used for data frames?', 'mcq', 1.00, 1),
(5, 5, 'Which color mode is best for print design?', 'mcq', 1.00, 1),
(6, 6, 'What does SEO stand for?', 'mcq', 1.00, 1),
(7, 7, 'Choose the correct sentence.', 'mcq', 1.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `pass_mark` decimal(5,2) DEFAULT 50.00,
  `time_limit_minutes` int(10) UNSIGNED DEFAULT NULL,
  `status` enum('draft','published') DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `course_id`, `title`, `description`, `pass_mark`, `time_limit_minutes`, `status`, `created_at`) VALUES
(1, 1, 'Basics Quiz', 'Check your basic understanding', 50.00, 10, 'published', '2026-11-21 13:19:27'),
(2, 2, 'HTML and CSS Quiz', 'Test your web fundamentals', 60.00, 15, 'published', '2026-11-21 13:19:27'),
(3, 3, 'Dart and Flutter Quiz', 'Dart language basics', 60.00, 15, 'published', '2026-11-21 13:19:27'),
(4, 4, 'Python Data Quiz', 'Python and pandas basics', 60.00, 20, 'published', '2026-11-21 13:19:27'),
(5, 5, 'Photoshop Basics Quiz', 'Tools and color modes', 50.00, 10, 'published', '2026-11-21 13:19:27'),
(6, 6, 'SEO Quiz', 'Digital marketing fundamentals', 50.00, 10, 'published', '2026-11-21 13:19:27'),
(7, 7, 'English Grammar Quiz', 'Basic grammar check', 50.00, 10, 'draft', '2026-11-21 13:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `started_at` datetime DEFAULT current_timestamp(),
  `submitted_at` datetime DEFAULT NULL,
  `score` decimal(7,2) DEFAULT 0.00,
  `total_marks` decimal(7,2) DEFAULT 0.00,
  `status` enum('in_progress','submitted','graded') DEFAULT 'in_progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_attempts`
--

INSERT INTO `quiz_attempts` (`id`, `quiz_id`, `student_id`, `started_at`, `submitted_at`, `score`, `total_marks`, `status`) VALUES
(1, 1, 2, '2026-11-12 10:00:00', '2026-11-12 10:06:00', 1.00, 1.00, 'graded'),
(2, 2, 2, '2026-11-14 11:00:00', '2026-11-14 11:09:00', 1.00, 1.00, 'graded'),
(3, 3, 5, '2026-11-15 15:00:00', '2026-11-15 15:12:00', 1.00, 1.00, 'graded'),
(4, 1, 5, '2026-11-16 09:30:00', '2026-11-16 09:35:00', 0.00, 1.00, 'graded'),
(5, 2, 6, '2026-11-17 19:00:00', '2026-11-17 19:10:00', 1.00, 1.00, 'graded'),
(6, 4, 6, '2026-11-18 20:00:00', '2026-11-18 20:15:00', 1.00, 1.00, 'submitted'),
(7, 5, 7, '2026-11-21 08:00:00', NULL, 0.00, 1.00, 'in_progress');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_options`
--

CREATE TABLE `quiz_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question_id` bigint(20) UNSIGNED NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `sort_order` int(10) UNSIGNED DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quiz_options`
--

INSERT INTO `quiz_options` (`id`, `question_id`, `option_text`, `is_correct`, `sort_order`) VALUES
(1, 1, 'Hyper Text Markup Language', 1, 1),
(2, 1, 'High Tech Modern Language', 0, 2),
(3, 1, 'Home Tool Markup Language', 0, 3),
(4, 1, 'Hyperlinks and Text Marking Language', 0, 4),
(5, 2, 'color', 1, 1),
(6, 2, 'font-style', 0, 2),
(7, 2, 'text-align', 0, 3),
(8, 2, 'background', 0, 4),
(9, 3, 'Dart', 1, 1),
(10, 3, 'Java', 0, 2),
(11, 3, 'Swift', 0, 3),
(12, 3, 'Kotlin', 0, 4),
(13, 4, 'pandas', 1, 1),
(14, 4, 'requests', 0, 2),
(15, 4, 'flask', 0, 3),
(16, 4, 'pygame', 0, 4),
(17, 5, 'CMYK', 1, 1),
(18, 5, 'RGB', 0, 2),
(19, 5, 'HSL', 0, 3),
(20, 5, 'Grayscale only', 0, 4),
(21, 6, 'Search Engine Optimization', 1, 1),
(22, 6, 'Social Engine Optimization', 0, 2),
(23, 6, 'Site Early Optimization', 0, 3),
(24, 6, 'Search Email Operation', 0, 4),
(25, 7, 'She goes to school every day.', 1, 1),
(26, 7, 'She go to school every day.', 0, 2),
(27, 7, 'She going to school every day.', 0, 3),
(28, 7, 'She gone to school every day.', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `email` varchar(190) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `role` enum('admin','instructor','student') NOT NULL DEFAULT 'student',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `profile_image`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Admin User', 'admin@gmail.com', '$2y$10$Hs5GkbbgH5gaORr2Feo7g.Ld0gZqSH3ve1Xgz009e2yTa8.E5wpDq', '01700000001', NULL, 'admin', 'active', '2026-11-07 17:31:19', '2026-11-21 12:13:11'),
(2, 'Karim Ahmed', 'student@gmail.com', '$2y$10$7GCmo9zwsaELvlUOi/VB0OlxQxLMejEKhUU0b1UPFV6tk6lOe6Oge', '01700000002', NULL, 'student', 'active', '2026-11-07 17:31:53', '2026-11-21 12:12:52'),
(3, 'Rahim Uddin', 'instructor@gmail.com', '$2y$10$XrptGL54IugW5txsCJ7cq.C0iqgg.pX4bgdIkxAQ3/jkeB6Q7MMUa', '01700000003', NULL, 'instructor', 'active', '2026-11-08 03:44:05', '2026-11-21 12:18:10'),
(4, 'Nusrat Jahan', 'nusrat@gmail.com', '$2y$10$7GCmo9zwsaELvlUOi/VB0OlxQxLMejEKhUU0b1UPFV6tk6lOe6Oge', '01700000004', NULL, 'instructor', 'active', '2026-11-21 13:19:27', '2026-11-21 13:19:27'),
(5, 'Sadia Islam', 'sadia@gmail.com', '$2y$10$7GCmo9zwsaELvlUOi/VB0OlxQxLMejEKhUU0b1UPFV6tk6lOe6Oge', '01700000005', NULL, 'student', 'active', '2026-11-21 13:19:27', '2026-11-21 13:19:27'),
(6, 'Tanvir Hossain', 'tanvir@gmail.com', '$2y$10$7GCmo9zwsaELvlUOi/VB0OlxQxLMejEKhUU0b1UPFV6tk6lOe6Oge', '01700000006', NULL, 'student', 'active', '2026-11-21 13:19:27', '2026-11-21 13:19:27'),
(7, 'Mitu Akter', 'mitu@gmail.com', '$2y$10$7GCmo9zwsaELvlUOi/VB0OlxQxLMejEKhUU0b1UPFV6tk6lOe6Oge', '01700000007', NULL, 'student', 'active', '2026-11-21 13:19:27', '2026-11-21 13:19:27');

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `student_id`, `course_id`, `created_at`) VALUES
(1, 2, 3, '2026-11-21 13:19:27'),
(2, 2, 4, '2026-11-21 13:19:27'),
(3, 5, 2, '2026-11-21 13:19:27'),
(4, 5, 5, '2026-11-21 13:19:27'),
(5, 6, 6, '2026-11-21 13:19:27'),
(6, 7, 3, '2026-11-21 13:19:27'),
(7, 7, 4, '2026-11-21 13:19:27');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `enrollment_id` (`enrollment_id`),
  ADD UNIQUE KEY `certificate_code` (`certificate_code`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_courses_instructor` (`instructor_id`),
  ADD KEY `fk_courses_category` (`category_id`),
  ADD KEY `idx_courses_status_category` (`status`,`category_id`);

--
-- Indexes for table `course_reviews`
--
ALTER TABLE `course_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_sections`
--
ALTER TABLE `course_sections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sections_course_order` (`course_id`,`sort_order`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_student_course` (`student_id`,`course_id`),
  ADD KEY `idx_enrollments_course_status` (`course_id`,`status`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_lessons_section_order` (`section_id`,`sort_order`);

--
-- Indexes for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_student_lesson` (`student_id`,`lesson_id`);

--
-- Indexes for table `live_classes`
--
ALTER TABLE `live_classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_live_classes_instructor` (`instructor_id`),
  ADD KEY `idx_live_classes_course_start` (`course_id`,`starts_at`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_ref` (`transaction_ref`),
  ADD KEY `fk_payments_enrollment` (`enrollment_id`),
  ADD KEY `idx_payments_status` (`payment_status`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_questions_quiz_order` (`quiz_id`,`sort_order`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_quizzes_course` (`course_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attempts_quiz` (`quiz_id`),
  ADD KEY `idx_attempts_student_quiz` (`student_id`,`quiz_id`);

--
-- Indexes for table `quiz_options`
--
ALTER TABLE `quiz_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_quiz_options_question` (`question_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_wishlist_student_course` (`student_id`,`course_id`),
  ADD KEY `fk_wishlists_course` (`course_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `course_reviews`
--
ALTER TABLE `course_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `course_sections`
--
ALTER TABLE `course_sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `lesson_progress`
--
ALTER TABLE `lesson_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `live_classes`
--
ALTER TABLE `live_classes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `quiz_options`
--
ALTER TABLE `quiz_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
