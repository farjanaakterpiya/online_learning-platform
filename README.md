📚 Online Learning Platform (LMS)A feature-rich, web-based Learning Management System (LMS) designed to streamline online education. This platform connects Students, Instructors, and Administrators in an interactive environment where instructors can publish course modules, conduct live sessions, and manage assessments, while students can enroll, track learning, take quizzes, and earn certificates.📌 Table of ContentsProject OverviewKey FeaturesUser Roles & ResponsibilitiesDatabase ArchitectureER DiagramTechnology StackInstallation & Setup GuideSystem WorkflowFuture EnhancementsAuthor & Academic Details📄 Project OverviewThe Online Learning Platform aims to simplify online learning by offering structured course management, secure transactions, interactive quiz engines, and live class integration. Inspirations and UI/UX flows are referenced from modern learning platforms like SkillSet.Database Name: online_learningDatabase Engine: MariaDB / MySQLArchitecture: 15 Relational Tables with Primary & Foreign Key ConstraintsPlatform Compatibility: Web Browsers (Responsive Desktop & Mobile)🚀 Key Features🔐 User Authentication & Role Management: Secure sign-up, sign-in, and role-based access for Admins, Instructors, and Students.🎓 Modular Course Builder: Categorized course creation with structured sections/chapters and rich text/video lessons.💳 Enrollment & Financial Management: Real-time enrollment tracking and payment transaction records.✍️ Interactive Quiz Engine: Quiz creation with multiple-choice questions, auto-evaluation, score tracking, and attempt logs.📹 Live Class Scheduling: Built-in scheduling for interactive live sessions via external link integrations (Zoom/Google Meet).📜 Certificate Generation: Automated certificate tracking and issue management for completing courses.⭐ Wishlist & Review System: Student rating systems, course feedback, and personal wishlist saving.👥 User Roles & ResponsibilitiesRoleCore ResponsibilitiesAdministratorManages users, course categories, platform compliance, overall dynamic data, and system analytics.InstructorPublishes courses, structures chapters & lessons, schedules live classes, and monitors quiz assessments.StudentBrowses catalog, enrolls in courses, makes payments, streams lessons, attends live sessions, takes quizzes, and writes reviews.🗄️ Database ArchitectureThe application relies on a normalized relational database containing 15 interlinked tables:online_learning
├── users               (Stores credentials for Admin, Instructor, Student)
├── categories          (Groups courses by subject areas)
├── courses             (Main metadata for all published courses)
├── course_sections     (Organizes course chapters/modules)
├── lessons             (Holds lesson contents and video URLs)
├── enrollments         (Tracks student course enrollments)
├── payments            (Stores transaction IDs, payment methods, and statuses)
├── quizzes             (Assessments linked to specific courses)
├── questions           (Individual questions tied to quizzes)
├── quiz_options        (Multiple-choice answers with correctness flags)
├── quiz_attempts       (Tracks student scores and quiz completion logs)
├── live_classes        (Stores live session links and schedule timings)
├── certificates        (Stores generated certificate numbers and dates)
├── course_reviews      (Student ratings and qualitative reviews)
└── wishlists           (Stores bookmarked courses per student)
Key Relationshipsusers ➔ courses: One-to-Many (One instructor creates multiple courses)courses ➔ course_sections ➔ lessons: Hierarchical content structureusers + courses ➔ enrollments & payments: Composite transaction mappingquizzes ➔ questions ➔ quiz_options: Multi-level assessment hierarchy📊 ER Diagram+-------------------+        +-------------------+        +-------------------+
|    CATEGORIES     |        |       USERS       |        |   LIVE_CLASSES    |
+-------------------+        +-------------------+        +-------------------+
| PK id             |<---|   | PK id             |   |--->| PK id             |
|    category_name  |    |   |    name, email    |   |    | FK course_id      |
+-------------------+    |   |    role           |   |    | FK instructor_id  |
          |              |   +-------------------+   |    +-------------------+
          | 1:N          |             |             |
          v              |             | 1:N         |
+-------------------+    |             v             |
|      COURSES      |----+--- [ ENROLLMENTS / PAYMENTS / WISHLISTS / REVIEWS ]
+-------------------+
| PK id             |
| FK instructor_id  |----> (Links to USERS.id)
| FK category_id    |
+-------------------+
          |
          +-----------------------------+
          | 1:N                         | 1:N
          v                             v
+-------------------+         +-------------------+
|  COURSE_SECTIONS  |         |      QUIZZES      |
+-------------------+         +-------------------+
| PK id             |         | PK id             |
| FK course_id      |         | FK course_id      |
+-------------------+         +-------------------+
          |                             |
          v 1:N                         v 1:N
+-------------------+         +-------------------+
|      LESSONS      |         |     QUESTIONS     |
+-------------------+         +-------------------+
| PK id             |         | PK id             |
| FK section_id     |         | FK quiz_id        |
+-------------------+         +-------------------+
                                        |
                                        v 1:N
                              +-------------------+
                              |   QUIZ_OPTIONS    |
                              +-------------------+
                              | PK id             |
                              | FK question_id    |
                              +-------------------+
🛠️ Technology StackFrontend: HTML5, CSS3, JavaScript (ES6+)Backend: PHP (Server-side Business Logic)Database: MySQL / MariaDBDevelopment Environment: XAMPP / Laragon / WampServerDatabase Tooling: phpMyAdmin⚙️ Installation & Setup GuidePrerequisitesWeb Server (XAMPP / Laragon / Apache)PHP (v7.4 or higher recommended)MySQL Database EngineSetup StepsClone the repository:git clone https://github.com/your-username/online-learning-platform.git
cd online-learning-platform
Move files to Web Server Directory:
Move the cloned project folder into your web server's root directory:XAMPP: C:/xampp/htdocs/Laragon: C:/laragon/www/Database Configuration:Open phpMyAdmin (http://localhost/phpmyadmin).Create a new database named online_learning.Click on the Import tab and upload the SQL dump file (online_learning.sql) included in the project.Update DB Connection:
Open the database connection file (e.g., config/db.php) and configure your local settings:$host = "localhost";
$user = "root";
$password = "";
$dbname = "online_learning";
Launch the Application:
Open your browser and navigate to:
http://localhost/online-learning-platform🔄 System WorkflowRegistration & Auth: User signs up and selects/receives a system role (Student, Instructor, Admin).Content Creation: Instructor defines course details, categorizes them, creates chapters, and uploads content.Enrollment & Payment: Student browses course marketplace, enrolls, and completes payment checkout.Learning & Evaluation: Student consumes lesson materials, attends scheduled live sessions, and completes quizzes.Completion & Rewards: Upon passing tests and reviewing the course, students receive certificates.🔮 Future Enhancements[ ] Interactive lesson progress percentage and course completion rules.[ ] Direct integrated payment gateways (bKash, Nagad, Stripe).[ ] Real-time notification and messaging system between instructors and students.[ ] Automated downloadable PDF certificate generator.[ ] Advanced analytics and graphical reporting for Administrators.🎓 Author & Academic DetailsStudent Name: Farjana AkterStudent ID: 1297023Batch: PWAD 73Project Type: Academic Web Application ProjectDeveloped for academic evaluation and demonstration.
