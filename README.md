# Online Learning Platform

A web-based Learning Management System (LMS) designed to bring students,
instructors, and administrators together in one organized learning
environment.

## Project Overview

The **Online Learning Platform** allows students to explore courses,
enroll in courses, study lessons, join scheduled live classes, take
quizzes, and receive certificates. Instructors can create courses and
manage learning materials, while administrators manage users, courses,
categories, and overall platform information.

The project is planned with reference to the general features of the
**SkillSet -- Online Learning Platform**. The database design documented
for this project is based on the supplied SQL structure; it does not
claim to reproduce SkillSet's private original database.

## Project Information

  Item                       Details
  -------------------------- --------------------------------------
  Project Name               Online Learning Platform
  Project Type               Web-based Learning Management System
  Database Name              `online_learning`
  Database Server            MariaDB / MySQL
  Development Technologies   PHP, MySQL, HTML, CSS, JavaScript
  Reference Application      SkillSet -- Online Learning Platform

## Main Objectives

-   Provide a single platform for online learning.
-   Allow students to register, log in, and manage learning activities.
-   Allow instructors to create courses and add sections and lessons.
-   Support course enrollment and payment records.
-   Provide quizzes, questions, answer options, and quiz results.
-   Support live class schedules, course reviews, wishlists, and
    certificates.
-   Organize information through a relational MySQL database.

## User Roles

  -----------------------------------------------------------------------
  Role                                Responsibilities
  ----------------------------------- -----------------------------------
  Administrator                       Manages users, courses, categories,
                                      and overall platform information.

  Instructor                          Creates courses, adds course
                                      content, schedules live classes,
                                      and supports learners.

  Student                             Browses courses, enrolls, makes
                                      payments, studies lessons, takes
                                      quizzes, and reviews courses.
  -----------------------------------------------------------------------

## Main Features

-   **User Management:** Registration, login, user roles, and account
    status.
-   **Course Management:** Course title, description, price, thumbnail,
    category, and publication status.
-   **Course Content:** Course sections and lessons with video links and
    learning content.
-   **Enrollment:** Records which students have enrolled in which
    courses.
-   **Payment Management:** Stores payment amount, method, transaction
    ID, and payment status.
-   **Quiz System:** Stores quizzes, questions, answer options, and
    correct-answer indicators.
-   **Quiz Results:** Stores student quiz attempts and obtained marks.
-   **Live Classes:** Stores class titles, meeting links, and scheduled
    start times.
-   **Certificates:** Stores certificate numbers and issue dates.
-   **Reviews and Wishlist:** Allows students to review courses and save
    courses for later.

## Database Design

The database is named `online_learning` and contains **15 relational
tables** connected through primary keys (PK) and foreign keys (FK).

  ------------------------------------------------------------------------
                            \# Table                 Purpose
  ---------------------------- --------------------- ---------------------
                             1 `users`               Stores administrator,
                                                     instructor, and
                                                     student accounts.

                             2 `categories`          Stores course
                                                     categories.

                             3 `courses`             Stores course details
                                                     and links each course
                                                     to an instructor and
                                                     category.

                             4 `course_sections`     Stores course
                                                     sections or chapters.

                             5 `lessons`             Stores lesson titles,
                                                     video URLs, and
                                                     lesson content.

                             6 `enrollments`         Stores student course
                                                     enrollment records.

                             7 `payments`            Stores student
                                                     payments for courses.

                             8 `quizzes`             Stores quizzes
                                                     associated with
                                                     courses.

                             9 `questions`           Stores questions
                                                     belonging to quizzes.

                            10 `quiz_options`        Stores answer options
                                                     and identifies the
                                                     correct option.

                            11 `quiz_attempts`       Stores student quiz
                                                     attempts and obtained
                                                     marks.

                            12 `certificates`        Stores certificate
                                                     details for students
                                                     and courses.

                            13 `live_classes`        Stores live class
                                                     details and meeting
                                                     links.

                            14 `course_reviews`      Stores student
                                                     ratings and written
                                                     reviews for courses.

                            15 `wishlists`           Stores courses saved
                                                     by students.
  ------------------------------------------------------------------------

### Key Relationships

-   One instructor can create many courses.
-   One category can contain many courses.
-   One course can contain many sections, quizzes, and enrolled
    students.
-   One section can contain many lessons.
-   One quiz can contain many questions.
-   One question can have multiple answer options.
-   Students can make quiz attempts, enroll in courses, review courses,
    and save courses to wishlists.
-   Payment, certificate, and live-class records are linked to their
    related students, courses, or instructors.

## Technology Stack

  Technology        Purpose
  ----------------- ----------------------------------------------------
  HTML              Creates the structure of web pages.
  CSS               Controls design and layout.
  JavaScript        Adds client-side interaction.
  PHP               Handles server-side logic and database operations.
  MySQL / MariaDB   Stores and manages application data.
  Laragon / XAMPP   Provides a local development environment.
  phpMyAdmin        Helps create and manage the database.

## System Workflow

1.  A user registers and logs in to the platform.
2.  The instructor creates a course and selects a category.
3.  The instructor adds course sections and lessons.
4.  The student browses available courses and selects a course.
5.  The student enrolls and the payment information is recorded.
6.  The student studies lessons and can join scheduled live classes.
7.  The student takes quizzes; questions, options, and marks are stored.
8.  The platform can store course reviews and certificate details.

## Getting Started

### Requirements

-   PHP-compatible local server environment (XAMPP or Laragon)
-   MySQL or MariaDB
-   phpMyAdmin (recommended for database management)
-   A web browser

### Installation

1.  Clone or download this repository.

    ``` bash
    git clone <repository-url>
    ```

2.  Place the project folder in your local server's web root (for
    example, `htdocs` in XAMPP).

3.  Start Apache and MySQL from XAMPP or start the equivalent services
    in Laragon.

4.  Open phpMyAdmin and create a database named:

    ``` sql
    CREATE DATABASE online_learning;
    ```

5.  Import the project's supplied SQL file into the `online_learning`
    database.

6.  Configure the database connection in the project's PHP configuration
    file using your local database host, username, password, and
    database name.

7.  Open the project in your browser using your local server URL, for
    example:

    ``` text
    http://localhost/<project-folder>/
    ```

> **Note:** The proposal describes the database structure but does not
> include the application source code, SQL dump filename, database
> configuration filename, or exact project folder structure. Update the
> installation steps to match the files in your repository.

## Expected Benefits

-   Students can access course materials from one platform.
-   Instructors can organize and publish learning content.
-   Course enrollment and payment records are stored systematically.
-   Quizzes and results help track student learning.
-   Live class links, reviews, wishlists, and certificates improve the
    learning experience.
-   A relational database helps maintain organized and connected
    information.

## Future Improvements

-   Add lesson progress tracking and course completion rules.
-   Add more detailed payment and refund management.
-   Add notifications and messaging between students and instructors.
-   Add downloadable learning resources and attendance tracking.
-   Improve reporting and analytics for administrators.

## Project Information / Credits

  Item          Details
  ------------- --------------------------
  Prepared By   Farjana Akter
  Student ID    1297023
  Batch         PWAD 73
  Project       Online Learning Platform

------------------------------------------------------------------------

**Online Learning Platform** --- A database-driven foundation for an
organized online learning experience.
