<?php include_once("../db_config.php"); ?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> | EduSpace</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- Bootstrap 5 + Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
  <link rel="stylesheet" href="../assets/css/dashboard-sidebar.css">
</head>

<body class="dashboard-container">
    <header class="dash-topbar">
      <button class="dashboard-sidebar-toggle" type="button" aria-controls="dashboard-sidebar" aria-expanded="false" aria-label="Open navigation">
          <i class="bi bi-list" aria-hidden="true"></i>
      </button>
        <a class="brand" href="../index.html">
            <span class="brand-mark">E</span> EduSpace</a>
        <div class="topbar-right">
            <span class="role-chip">

            </span><a class="user-mini" href="../logout.php">Log out ↗</a>
        </div>
    </header>
    <div class="dashboard-layout">