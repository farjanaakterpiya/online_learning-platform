<?php
/*
 * Shared helpers for the student/ and instructor/ pages.
 * Include with:  require_once __DIR__ . '/../includes/auth.php';
 */
if (session_status() === PHP_SESSION_NONE) { session_start(); }
require_once __DIR__ . '/../db_config.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function e($v) { return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8'); }

function redirect($url) { header('Location: ' . $url); exit; }

function dashboard_url($role) {
    if ($role === 'admin')      return 'admin/admin_dashboard.php';
    if ($role === 'instructor') return 'admin/instructor_dashboard.php';
    return 'admin/student_dashboard.php';
}

/* ---------- prepared-statement helpers ---------- */
function db_run($sql, $types = '', $params = []) {
    global $conn;
    $stmt = $conn->prepare($sql);
    if ($types !== '') { $stmt->bind_param($types, ...$params); }
    $stmt->execute();
    return $stmt;
}
function db_all($sql, $types = '', $params = []) {
    return db_run($sql, $types, $params)->get_result()->fetch_all(MYSQLI_ASSOC);
}
function db_one($sql, $types = '', $params = []) {
    $rows = db_all($sql, $types, $params);
    return $rows[0] ?? null;
}

/* ---------- login / role guard ---------- */
function require_role(array $roles) {
    if (empty($_SESSION['email']) || !in_array($_SESSION['role'] ?? '', $roles, true)) {
        redirect('../login.php');
    }
    $user = db_one("SELECT id, name, email, role, status FROM users WHERE email=? LIMIT 1", 's', [$_SESSION['email']]);
    if (!$user || $user['status'] !== 'active' || !in_array($user['role'], $roles, true)) {
        redirect('../logout.php');
    }
    return $user;
}

/* ---------- CSRF + flash messages ---------- */
function csrf_token() {
    if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(16)); }
    return $_SESSION['csrf'];
}
function csrf_field() { return '<input type="hidden" name="csrf" value="' . csrf_token() . '">'; }
function csrf_check() {
    if (!hash_equals($_SESSION['csrf'] ?? '', (string) ($_POST['csrf'] ?? ''))) {
        http_response_code(400);
        exit('Invalid request token. Go back, refresh the page and try again.');
    }
}
function flash($message, $type = 'success') { $_SESSION['flash'] = [$message, $type]; }
function flash_show() {
    if (!empty($_SESSION['flash'])) {
        list($message, $type) = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($type) . '">' . e($message) . '</div>';
    }
}

/* ---------- page layout (reuses the existing dashboard design) ---------- */
function portal_start($title) {
    $pageTitle = $title;
    include __DIR__ . '/dashboard_header.php';
    include __DIR__ . '/dashboard_sidebar.php';
    echo '<main class="dashboard-content"><div class="dashboard-container">';
}
function portal_end() { include __DIR__ . '/dashboard_footer.php'; }

/* ---------- learning helpers ---------- */
function course_art($i) { $a = ['art-purple', 'art-peach', 'art-green']; return $a[$i % 3]; }

function youtube_embed($url) {
    if (preg_match('~(?:youtube\.com/watch\?v=|youtu\.be/|youtube\.com/embed/)([A-Za-z0-9_-]{11})~', (string) $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }
    return null;
}

/* Recalculate an enrollment's progress; complete it and issue a certificate at 100%. */
function recalc_progress($enrollmentId) {
    $en = db_one("SELECT id, course_id, status FROM enrollments WHERE id=?", 'i', [$enrollmentId]);
    if (!$en) { return 0; }
    $total = (int) db_one(
        "SELECT COUNT(l.id) AS t FROM lessons l JOIN course_sections s ON s.id=l.section_id WHERE s.course_id=?",
        'i', [$en['course_id']]
    )['t'];
    $done = (int) db_one(
        "SELECT COUNT(*) AS t FROM lesson_progress p
         JOIN lessons l ON l.id=p.lesson_id JOIN course_sections s ON s.id=l.section_id
         WHERE p.enrollment_id=? AND s.course_id=?",
        'ii', [$enrollmentId, $en['course_id']]
    )['t'];
    $pct = $total > 0 ? round($done * 100 / $total, 2) : 0;

    if ($total > 0 && $done >= $total) {
        db_run("UPDATE enrollments SET progress_percent=100, status='completed', completed_at=IFNULL(completed_at, NOW()) WHERE id=?", 'i', [$enrollmentId]);
        if (!db_one("SELECT id FROM certificates WHERE enrollment_id=?", 'i', [$enrollmentId])) {
            $code = 'EDU-' . strtoupper(bin2hex(random_bytes(5)));
            db_run("INSERT IGNORE INTO certificates (enrollment_id, certificate_code) VALUES (?, ?)", 'is', [$enrollmentId, $code]);
        }
    } else {
        $status = $en['status'] === 'completed' ? 'active' : $en['status'];
        db_run("UPDATE enrollments SET progress_percent=?, status=?, completed_at=NULL WHERE id=?", 'dsi', [$pct, $status, $enrollmentId]);
    }
    return $pct;
}

/* Instructor ownership check. */
function own_course($courseId, $instructorId) {
    return db_one("SELECT * FROM courses WHERE id=? AND instructor_id=?", 'ii', [(int) $courseId, (int) $instructorId]);
}

/* Only allow http(s) links to be printed as clickable URLs (blocks javascript: etc.). */
function safe_url($url) {
    $url = trim((string) $url);
    return preg_match('~^https?://~i', $url) ? $url : '';
}
