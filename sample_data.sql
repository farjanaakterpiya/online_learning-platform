-- Optional, repeatable demo data for a fresh online_learning.sql import.
-- On a fresh import, this provides seven rows in users, categories, courses,
-- course_sections, lessons, enrollments, quizzes and questions.
-- Demo password for every account: 123
--   admin@gmail.com
--   instructor@gmail.com
--   student@gmail.com
--   student2@gmail.com through student5@gmail.com
-- Demo hashes are legacy SHA-1 and are upgraded after the first successful login.

UPDATE `users` SET `name` = 'Admin User'      WHERE `id` = 1 AND `name` = '';
UPDATE `users` SET `name` = 'Demo Student'    WHERE `id` = 2 AND `name` = '';
UPDATE `users` SET `name` = 'Demo Instructor' WHERE `id` = 3 AND `name` = '';

INSERT IGNORE INTO `users` (`id`, `name`, `email`, `password`, `phone`, `role`, `status`) VALUES
(4, 'Demo Student 2', 'student2@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', NULL, 'student', 'active'),
(5, 'Demo Student 3', 'student3@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', NULL, 'student', 'active'),
(6, 'Demo Student 4', 'student4@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', NULL, 'student', 'active'),
(7, 'Demo Student 5', 'student5@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', NULL, 'student', 'active');

INSERT IGNORE INTO `categories` (`id`, `name`, `slug`, `description`, `status`) VALUES
(1, 'Web Development', 'web-development', 'HTML, CSS, JavaScript and PHP', 'active'),
(2, 'Design', 'design', 'UI / UX and graphic design', 'active'),
(3, 'Databases', 'databases', 'SQL and data management', 'active'),
(4, 'Python Programming', 'python-programming', 'Python programming from beginner to advanced', 'active'),
(5, 'Digital Marketing', 'digital-marketing', 'SEO, content and online campaigns', 'active'),
(6, 'Data Science', 'data-science', 'Data analysis and practical data skills', 'active'),
(7, 'Cloud Computing', 'cloud-computing', 'Cloud infrastructure and deployment basics', 'active');

INSERT IGNORE INTO `courses` (`id`, `instructor_id`, `category_id`, `title`, `slug`, `short_description`, `description`, `level`, `price`, `language`, `status`) VALUES
(1, 3, 1, 'Web Development Fundamentals', 'web-development-fundamentals', 'Learn the building blocks of modern websites.', 'Start from zero and build real web pages with HTML, CSS and a little JavaScript.', 'beginner', 0.00, 'English', 'published'),
(2, 3, 3, 'Database Management with MySQL', 'database-management-with-mysql', 'Understand data, tables and SQL queries.', 'Design tables, write queries and connect MySQL with PHP.', 'intermediate', 1500.00, 'English', 'published'),
(3, 3, 4, 'Python for Beginners', 'python-for-beginners', 'Learn Python fundamentals through practical examples.', 'Build a strong foundation in Python syntax, variables and functions.', 'beginner', 0.00, 'English', 'published'),
(4, 3, 2, 'UI/UX Design Foundations', 'ui-ux-design-foundations', 'Create clear, useful and attractive digital interfaces.', 'Learn visual hierarchy, spacing and the fundamentals of user-centered design.', 'beginner', 1200.00, 'English', 'published'),
(5, 3, 5, 'Digital Marketing Essentials', 'digital-marketing-essentials', 'Understand search visibility and online audiences.', 'Explore search engine optimization and practical marketing fundamentals.', 'beginner', 900.00, 'English', 'published'),
(6, 3, 6, 'Data Analysis with Python', 'data-analysis-with-python', 'Turn datasets into useful insights.', 'Practice reading and exploring structured data with Python.', 'intermediate', 1800.00, 'English', 'published'),
(7, 3, 7, 'Cloud Computing Basics', 'cloud-computing-basics', 'Learn the core ideas behind cloud platforms.', 'Understand cloud services, deployment and shared responsibility.', 'beginner', 1500.00, 'English', 'published');

INSERT IGNORE INTO `course_sections` (`id`, `course_id`, `title`, `description`, `sort_order`) VALUES
(1, 1, 'Getting started', 'What the web is and how pages are built', 1),
(2, 2, 'Database essentials', 'Tables, keys and relational data', 1),
(3, 3, 'Python essentials', 'Variables and core Python concepts', 1),
(4, 4, 'Design foundations', 'Visual hierarchy and clear interfaces', 1),
(5, 5, 'Marketing basics', 'Search visibility and audiences', 1),
(6, 6, 'Data analysis basics', 'Working with structured datasets', 1),
(7, 7, 'Cloud essentials', 'Cloud services and deployment basics', 1);

-- Re-map the original sample sections when this file is applied over old demo data.
UPDATE `course_sections`
SET `course_id` = 2, `title` = 'Database essentials', `description` = 'Tables, keys and relational data', `sort_order` = 1
WHERE `id` = 2 AND `title` = 'Styling with CSS';
UPDATE `course_sections`
SET `course_id` = 3, `title` = 'Python essentials', `description` = 'Variables and core Python concepts', `sort_order` = 1
WHERE `id` = 3 AND `title` = 'SQL basics';

INSERT IGNORE INTO `lessons` (`id`, `section_id`, `title`, `lesson_type`, `content`, `resource_url`, `duration_minutes`, `is_preview`, `sort_order`) VALUES
(1, 1, 'How the web works', 'article', 'A browser asks a server for a page. The server answers with HTML, CSS and JavaScript, and the browser turns that into the page you see.', NULL, 8, 1, 1),
(2, 2, 'Relational tables', 'article', 'A relational database stores data in tables. Each table has columns, and each row represents one record.', NULL, 12, 0, 1),
(3, 3, 'Python variables', 'article', 'Variables give names to values. Python can infer a value type from the data assigned to a variable.', NULL, 10, 0, 1),
(4, 4, 'Visual design hierarchy', 'article', 'Use size, contrast and spacing to help people find the most important information first.', NULL, 15, 0, 1),
(5, 5, 'Search visibility', 'article', 'Search engine optimization helps people discover useful pages through organic search results.', NULL, 9, 1, 1),
(6, 6, 'Explore a dataset', 'article', 'Inspect columns, check data quality and look for useful patterns before drawing conclusions.', NULL, 14, 0, 1),
(7, 7, 'Cloud service models', 'article', 'Cloud platforms provide on-demand computing services such as storage, databases and application hosting.', NULL, 11, 0, 1);

-- Re-map lessons from the original three-course sample data.
UPDATE `lessons`
SET `section_id` = 2, `title` = 'Relational tables', `lesson_type` = 'article',
    `content` = 'A relational database stores data in tables. Each table has columns, and each row represents one record.',
    `resource_url` = NULL, `duration_minutes` = 12, `is_preview` = 0, `sort_order` = 1
WHERE `id` = 2 AND `title` = 'Your first HTML page';
UPDATE `lessons`
SET `section_id` = 3, `title` = 'Python variables', `lesson_type` = 'article',
    `content` = 'Variables give names to values. Python can infer a value type from the data assigned to a variable.',
    `resource_url` = NULL, `duration_minutes` = 10, `is_preview` = 0, `sort_order` = 1
WHERE `id` = 3 AND `title` = 'CSS selectors';
UPDATE `lessons`
SET `section_id` = 4, `title` = 'Visual design hierarchy', `lesson_type` = 'article',
    `content` = 'Use size, contrast and spacing to help people find the most important information first.',
    `resource_url` = NULL, `duration_minutes` = 15, `is_preview` = 0, `sort_order` = 1
WHERE `id` = 4 AND `title` = 'Learn more on MDN';
UPDATE `lessons`
SET `section_id` = 5, `title` = 'Search visibility', `lesson_type` = 'article',
    `content` = 'Search engine optimization helps people discover useful pages through organic search results.',
    `resource_url` = NULL, `duration_minutes` = 9, `is_preview` = 1, `sort_order` = 1
WHERE `id` = 5 AND `title` = 'What is a table?';
UPDATE `lessons`
SET `section_id` = 6, `title` = 'Explore a dataset', `lesson_type` = 'article',
    `content` = 'Inspect columns, check data quality and look for useful patterns before drawing conclusions.',
    `resource_url` = NULL, `duration_minutes` = 14, `is_preview` = 0, `sort_order` = 1
WHERE `id` = 6 AND `title` = 'SELECT queries';

INSERT IGNORE INTO `enrollments` (`student_id`, `course_id`, `enrolled_at`, `status`, `progress_percent`) VALUES
(2, 1, NOW(), 'active', 0.00),
(4, 2, NOW(), 'active', 0.00),
(5, 3, NOW(), 'active', 0.00),
(6, 4, NOW(), 'active', 0.00),
(7, 5, NOW(), 'active', 0.00),
(2, 6, NOW(), 'active', 0.00),
(4, 7, NOW(), 'active', 0.00);

INSERT IGNORE INTO `quizzes` (`id`, `course_id`, `title`, `description`, `pass_mark`, `time_limit_minutes`, `status`) VALUES
(1, 1, 'Web basics check', 'A quick question on web fundamentals.', 60.00, 10, 'published'),
(2, 2, 'Database essentials check', 'Review the basics of relational data.', 60.00, 10, 'published'),
(3, 3, 'Python essentials check', 'Review variables and Python syntax.', 60.00, 10, 'published'),
(4, 4, 'Design foundations check', 'Review visual hierarchy and interface design.', 60.00, 10, 'published'),
(5, 5, 'Digital marketing check', 'Review search engine optimization basics.', 60.00, 10, 'published'),
(6, 6, 'Data analysis check', 'Review tools used to explore datasets.', 60.00, 10, 'published'),
(7, 7, 'Cloud computing check', 'Review core cloud computing concepts.', 60.00, 10, 'published');

UPDATE `quizzes`
SET `description` = 'A quick question on web fundamentals.'
WHERE `id` = 1 AND `description` = 'Three quick questions on what you have learned.';

INSERT IGNORE INTO `questions` (`id`, `quiz_id`, `question_text`, `question_type`, `marks`, `sort_order`) VALUES
(1, 1, 'Which language is used to style a web page?', 'mcq', 1.00, 1),
(2, 2, 'A primary key uniquely identifies each row.', 'true_false', 1.00, 1),
(3, 3, 'Which keyword defines a function in Python?', 'short_answer', 1.00, 1),
(4, 4, 'What helps establish visual hierarchy in an interface?', 'mcq', 1.00, 1),
(5, 5, 'What does SEO stand for?', 'mcq', 1.00, 1),
(6, 6, 'Which Python library is commonly used for tabular data?', 'mcq', 1.00, 1),
(7, 7, 'Which service model provides virtual machines?', 'mcq', 1.00, 1);

-- Re-map original quiz questions when this file is applied over old demo data.
UPDATE `questions`
SET `quiz_id` = 2, `question_text` = 'A primary key uniquely identifies each row.',
    `question_type` = 'true_false', `sort_order` = 1
WHERE `id` = 2 AND `question_text` = 'HTML is a programming language.';
UPDATE `questions`
SET `quiz_id` = 3, `question_text` = 'Which keyword defines a function in Python?',
    `question_type` = 'short_answer', `sort_order` = 1
WHERE `id` = 3 AND `question_text` = 'Which CSS selector targets an element by its class? (type the symbol)';

INSERT IGNORE INTO `quiz_options` (`id`, `question_id`, `option_text`, `is_correct`, `sort_order`) VALUES
(1, 1, 'HTML', 0, 1),
(2, 1, 'CSS', 1, 2),
(3, 1, 'SQL', 0, 3),
(4, 2, 'True', 1, 1),
(5, 2, 'False', 0, 2),
(6, 3, 'def', 1, 1),
(7, 4, 'Size, contrast and spacing', 1, 1),
(8, 4, 'Using the same font size everywhere', 0, 2),
(9, 4, 'Removing all whitespace', 0, 3),
(10, 5, 'Search Engine Optimization', 1, 1),
(11, 5, 'Secure Email Operation', 0, 2),
(12, 5, 'Simple Editing Option', 0, 3),
(13, 6, 'pandas', 1, 1),
(14, 6, 'turtle', 0, 2),
(15, 6, 'tkinter', 0, 3),
(16, 7, 'Infrastructure as a Service', 1, 1),
(17, 7, 'Software as a Service', 0, 2),
(18, 7, 'Platform as a Service', 0, 3);

-- Correct the answer options if the old sample data was already imported.
UPDATE `quiz_options` SET `is_correct` = 1 WHERE `id` = 4 AND `question_id` = 2 AND `option_text` = 'True';
UPDATE `quiz_options` SET `is_correct` = 0 WHERE `id` = 5 AND `question_id` = 2 AND `option_text` = 'False';
UPDATE `quiz_options` SET `option_text` = 'def' WHERE `id` = 6 AND `question_id` = 3 AND `option_text` = '.';

INSERT IGNORE INTO `live_classes` (`id`, `course_id`, `instructor_id`, `title`, `meeting_url`, `starts_at`, `status`) VALUES
(1, 1, 3, 'Live Q&A: your first website', 'https://meet.google.com/', DATE_ADD(NOW(), INTERVAL 3 DAY), 'scheduled');
