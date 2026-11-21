-- OPTIONAL demo data so every page has something to show. Safe to run more than once.
-- Demo logins (password for all three = 123):
--   admin@gmail.com   instructor@gmail.com   student@gmail.com

UPDATE `users` SET `name` = 'Admin User'      WHERE `id` = 1 AND `name` = '';
UPDATE `users` SET `name` = 'Demo Student'    WHERE `id` = 2 AND `name` = '';
UPDATE `users` SET `name` = 'Demo Instructor' WHERE `id` = 3 AND `name` = '';

INSERT IGNORE INTO `categories` (`id`, `name`, `slug`, `description`, `status`) VALUES
(1, 'Web Development', 'web-development', 'HTML, CSS, JavaScript and PHP', 'active'),
(2, 'Design', 'design', 'UI / UX and graphic design', 'active'),
(3, 'Databases', 'databases', 'SQL and data management', 'active');

INSERT IGNORE INTO `courses` (`id`, `instructor_id`, `category_id`, `title`, `slug`, `short_description`, `description`, `level`, `price`, `language`, `status`) VALUES
(1, 3, 1, 'Web Development Fundamentals', 'web-development-fundamentals', 'Learn the building blocks of modern websites.', 'Start from zero and build real web pages with HTML, CSS and a little JavaScript.', 'beginner', 0.00, 'English', 'published'),
(2, 3, 3, 'Database Management with MySQL', 'database-management-with-mysql', 'Understand data, tables and SQL queries.', 'Design tables, write queries and connect MySQL with PHP.', 'intermediate', 1500.00, 'English', 'published');

INSERT IGNORE INTO `course_sections` (`id`, `course_id`, `title`, `description`, `sort_order`) VALUES
(1, 1, 'Getting started', 'What the web is and how pages are built', 1),
(2, 1, 'Styling with CSS', 'Make your pages look good', 2),
(3, 2, 'SQL basics', 'Tables, rows and simple queries', 1);

INSERT IGNORE INTO `lessons` (`id`, `section_id`, `title`, `lesson_type`, `content`, `resource_url`, `duration_minutes`, `is_preview`, `sort_order`) VALUES
(1, 1, 'How the web works', 'article', 'A browser asks a server for a page. The server answers with HTML, CSS and JavaScript, and the browser turns that into the page you see.', NULL, 8, 1, 1),
(2, 1, 'Your first HTML page', 'article', 'Every page starts with a doctype, an html element, a head and a body. Put your content inside the body.', NULL, 12, 0, 2),
(3, 2, 'CSS selectors', 'article', 'Selectors choose which elements a rule applies to: element, .class and #id selectors are the three you will use most.', NULL, 10, 0, 1),
(4, 2, 'Learn more on MDN', 'link', 'Read the official guide, then come back and mark this lesson complete.', 'https://developer.mozilla.org/en-US/docs/Learn', 15, 0, 2),
(5, 3, 'What is a table?', 'article', 'A table stores rows of data. Each column has a name and a data type.', NULL, 9, 1, 1),
(6, 3, 'SELECT queries', 'article', 'SELECT reads rows from a table. Add WHERE to filter and ORDER BY to sort.', NULL, 14, 0, 2);

INSERT IGNORE INTO `quizzes` (`id`, `course_id`, `title`, `description`, `pass_mark`, `time_limit_minutes`, `status`) VALUES
(1, 1, 'Web basics check', 'Three quick questions on what you have learned.', 60.00, 10, 'published');

INSERT IGNORE INTO `questions` (`id`, `quiz_id`, `question_text`, `question_type`, `marks`, `sort_order`) VALUES
(1, 1, 'Which language is used to style a web page?', 'mcq', 1.00, 1),
(2, 1, 'HTML is a programming language.', 'true_false', 1.00, 2),
(3, 1, 'Which CSS selector targets an element by its class? (type the symbol)', 'short_answer', 1.00, 3);

INSERT IGNORE INTO `quiz_options` (`id`, `question_id`, `option_text`, `is_correct`, `sort_order`) VALUES
(1, 1, 'HTML', 0, 1),
(2, 1, 'CSS', 1, 2),
(3, 1, 'SQL', 0, 3),
(4, 2, 'True', 0, 1),
(5, 2, 'False', 1, 2),
(6, 3, '.', 1, 1);

INSERT IGNORE INTO `live_classes` (`id`, `course_id`, `instructor_id`, `title`, `meeting_url`, `starts_at`, `status`) VALUES
(1, 1, 3, 'Live Q&A: your first website', 'https://meet.google.com/', DATE_ADD(NOW(), INTERVAL 3 DAY), 'scheduled');
