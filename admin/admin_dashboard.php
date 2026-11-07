<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>

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
            background: #172554;
            color: white;
            position: fixed;
            padding: 25px 20px;
        }

        .sidebar h2 {
            margin-bottom: 35px;
            color: #60a5fa;
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
            background: #2563eb;
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
            color: #2563eb;
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

<!-- <?php include 'sidbar.php';?> -->

<div class="sidebar">

    <h2>LearnHub</h2>

    <a href="instructor_dashboard.php">Dashboard</a>
    <a href="my_courses.php">My Courses</a>
    <a href="add_course.php">Add Course</a>
    <a href="lessons.php">Lessons</a>
    <a href="students.php">My Students</a>
    <a href="quiz.php">Quizzes</a>
    <a href="profile.php">My Profile</a>
    <a href="logout.php">Logout</a>

</div>

<div class="main">

    <div class="header">
        <h2>admin Dashboard</h2>
        <p>Welcome, to online learning platform</p>
    </div>

    <div class="cards">

        <div class="card">
            <h3>My Courses</h3>
            <h1>8</h1>
        </div>

        <div class="card">
            <h3>Total Students</h3>
            <h1>120</h1>
        </div>

        <div class="card">
            <h3>Total Lessons</h3>
            <h1>35</h1>
        </div>

        <div class="card">
            <h3>Total Quizzes</h3>
            <h1>12</h1>
        </div>

    </div>

</div>

</body>
</html>