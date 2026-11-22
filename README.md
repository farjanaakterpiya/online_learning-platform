# EduSpace — Online Learning Platform

A PHP/MySQL Learning Management System (LMS) for students, instructors and administrators.

## Included features

- Public course catalogue and course detail/search experience
- Student registration and sign-in
- Role-based dashboards (administrator, instructor, student)
- Course/category, section and lesson management
- Enrollment and payment records
- Quizzes, questions, options and attempts
- Live class schedule, course reviews, wishlists and certificates
- Responsive interface using Bootstrap, custom CSS and JavaScript

## Requirements

- PHP 8.0+ (mysqli extension enabled)
- MySQL 8+ or compatible MariaDB
- XAMPP or Laragon
- Modern browser

## Local installation (XAMPP)

1. Extract the project into `htdocs` (example: `C:\xampp\htdocs\online_learning-platform`).
2. Start **Apache** and **MySQL** in XAMPP.
3. Open phpMyAdmin (`http://localhost/phpmyadmin`).
4. Create a database named **online_learning** (utf8mb4 recommended).
5. Import `online_learning.sql` into that database.
6. Run `update_db.sql` in phpMyAdmin with `online_learning` selected. It is safe on a fresh import and creates tables needed by review and lesson-progress features on older imports.
7. Optional demo content: import `sample_data.sql`. It adds seven demo records for users, categories, courses, course sections, lessons, enrollments, quizzes and questions, plus usable quiz options. See the SQL comments for demo credentials.
8. If database credentials differ from XAMPP defaults, edit `db_config.php`.
9. Open `http://localhost/online_learning-platform/`.

## Database files

- `online_learning.sql` — schema and relationships; import first.
- `update_db.sql` — adds missing review and lesson-progress tables; run after the schema and before optional demo data.
- `sample_data.sql` — optional, repeatable sample content; import after the schema update.

## Registration and login

- New registrations create **student** accounts only.
- New and changed passwords use PHP's `password_hash()`. Existing SHA-1 demo passwords continue to work and are upgraded after successful login.
- Admin and instructor roles should be assigned by an administrator; public registration cannot create privileged accounts.

## Folder overview

- `admin/` — role dashboards
- `instructor/` — instructor tools
- `student/` — student learning features
- `crud/` — database record management pages
- `includes/` — shared auth, layouts, sidebars
- `assets/` — CSS and JavaScript

## Security notes for local/demo use

This is an educational project. Before public hosting, review all CRUD routes and permissions, add/verify CSRF protection on every state-changing form, validate ownership of instructor/student records, use a secure payment provider (never store card data), configure HTTPS, and set production database credentials outside source control. Do not use demo credentials on a public server.

See `PROJECT_STRUCTURE.txt` for the complete file tree and `Online_Learning_Platform_proposal.pdf` for the proposal.


