<?php
$topbarScript = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$topbarRoot = strpos($topbarScript, '/crud/') !== false ? '../../' : '../';
$topbarDashboard = !empty($appTopbarDashboard);
$topbarRoleKey = (string) ($_SESSION['role'] ?? '');
$topbarRole = [
    'admin' => 'Administrator',
    'instructor' => 'Instructor',
    'student' => 'Student',
][$topbarRoleKey] ?? 'Member';
$topbarName = trim((string) ($_SESSION['name'] ?? ''));
if ($topbarName === '') {
    $topbarEmail = trim((string) ($_SESSION['email'] ?? ''));
    $topbarName = $topbarEmail !== '' ? ucfirst(explode('@', $topbarEmail, 2)[0]) : $topbarRole;
}
$topbarInitial = strtoupper(substr($topbarName, 0, 1));
?>
<header class="app-topbar">
  <?php if ($topbarDashboard): ?>
    <button class="app-topbar-menu dashboard-sidebar-toggle" type="button" aria-controls="dashboard-sidebar" aria-expanded="false" aria-label="Open navigation">
      <i class="bi bi-list" aria-hidden="true"></i>
    </button>
  <?php else: ?>
    <button class="app-topbar-menu app-topbar-menu--admin d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Open admin navigation">
      <i class="bi bi-list" aria-hidden="true"></i>
    </button>
  <?php endif; ?>
  <a class="app-topbar-brand" href="<?php echo $topbarRoot; ?>index.html">
    <span class="app-topbar-mark">E</span>
    <span>EduSpace</span>
  </a>
  <div class="app-topbar-actions">
    <a class="app-topbar-website" href="<?php echo $topbarRoot; ?>index.html" title="Visit website">
      <i class="bi bi-globe2" aria-hidden="true"></i><span>Website</span>
    </a>
    <div class="app-topbar-profile">
      <span class="app-topbar-avatar"><?php echo htmlspecialchars($topbarInitial, ENT_QUOTES, 'UTF-8'); ?></span>
      <span class="app-topbar-identity">
        <strong><?php echo htmlspecialchars($topbarName, ENT_QUOTES, 'UTF-8'); ?></strong>
        <small><?php echo htmlspecialchars($topbarRole, ENT_QUOTES, 'UTF-8'); ?></small>
      </span>
    </div>
    <a class="app-topbar-logout" href="<?php echo $topbarRoot; ?>logout.php">
      <i class="bi bi-box-arrow-right" aria-hidden="true"></i><span>Log out</span>
    </a>
  </div>
</header>
