<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f4f6fb;
        }

        .sidebar {
            width: 240px;
            height: 100vh;
            background: #581c87;
            color: white;
            position: fixed;
            padding: 25px 20px;
        }

        .sidebar h2 {
            margin-bottom: 35px;
            color: #d8b4fe;
        }

        .sidebar a {
            display: block;
            color: white;
            text-decoration: none;
            padding: 14px;
            margin-bottom: 8px;
            border-radius: 6px;
        }

        .sidebar a:hover {
            background: #9333ea;
        }

        .main {
            margin-left: 240px;
            padding: 30px;
        }

        .header {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 25px;
        }

        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
            gap: 20px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 3px 12px #00000010;
        }

        .card h3 {
            color: #64748b;
            margin-bottom: 15px;
        }

        .card h1 {
            color: #9333ea;
        }

        @media(max-width: 650px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>

<div class="sidebar">

    <h2>LearnHub</h2>

    <a href="student_dashboard.php">Dashboard</a>
    <a href="courses.php">Browse Courses</a>
    <a href="my_courses.php">My Courses</a>
    <a href="my_lessons.php">My Lessons</a>
    <a href="my_quizzes.php">My Quizzes</a>
    <a href="certificates.php">Certificates</a>
    <a href="profile.php">My Profile</a>
    <a href="logout.php">Logout</a>

</div>

<div class="main">

    <div class="header">
        <h2>Student Dashboard</h2>
        <p>Welcome, Student! Start Learning Today.</p>
    </div>

    <div class="cards">

        <div class="card">
            <h3>Enrolled Courses</h3>
            <h1>5</h1>
        </div>

        <div class="card">
            <h3>Completed Courses</h3>
            <h1>2</h1>
        </div>

        <div class="card">
            <h3>My Lessons</h3>
            <h1>20</h1>
        </div>

        <div class="card">
            <h3>My Certificates</h3>
            <h1>2</h1>
        </div>

    </div>

</div>

</body>
</html>