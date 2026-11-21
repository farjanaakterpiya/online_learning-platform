-- Run this ONCE in phpMyAdmin (database: online_learning) if you already imported online_learning.sql earlier.
-- It adds the table that tracks which lessons a student has completed.

CREATE TABLE IF NOT EXISTS `lesson_progress` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `enrollment_id` bigint UNSIGNED NOT NULL,
  `lesson_id` bigint UNSIGNED NOT NULL,
  `completed_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_progress_enrollment_lesson` (`enrollment_id`,`lesson_id`),
  KEY `fk_progress_lesson` (`lesson_id`),
  CONSTRAINT `fk_progress_enrollment` FOREIGN KEY (`enrollment_id`) REFERENCES `enrollments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_progress_lesson` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
