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

<?php include 'sidbar.php';?>

<div class="main">

    <div class="header">
        <h2>Admin Dashboard</h2>
        <p>Welcome to Online Learning Platform!</p>
    </div>

    <div class="cards">

        <div class="card">
            <h3>Total Students</h3>
            <h1>250</h1>
        </div>

        <div class="card">
            <h3>Total Instructors</h3>
            <h1>25</h1>
        </div>

        <div class="card">
            <h3>Total Courses</h3>
            <h1>40</h1>
        </div>

        <div class="card">
            <h3>Total Enrollments</h3>
            <h1>180</h1>
        </div>

    </div>

</div>

</body>
</html>