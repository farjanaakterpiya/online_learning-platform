document.addEventListener('DOMContentLoaded', function () {
    var menuToggle = document.querySelector('.menu-toggle');
    var nav = document.querySelector('.site-header nav');
    if (menuToggle && nav) {
        menuToggle.addEventListener('click', function () {
            nav.classList.toggle('nav-open');
        });
    }

    var sidebarToggle = document.querySelector('.dashboard-sidebar-toggle');
    var sidebarBackdrop = document.querySelector('.sidebar-backdrop');
    var dashboardSidebar = document.getElementById('dashboard-sidebar');
    if (!sidebarToggle || !sidebarBackdrop || !dashboardSidebar) {
        return;
    }

    function setSidebarOpen(isOpen) {
        document.body.classList.toggle('sidebar-open', isOpen);
        sidebarToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        sidebarToggle.setAttribute('aria-label', isOpen ? 'Close navigation' : 'Open navigation');
        sidebarToggle.innerHTML = isOpen
            ? '<i class="bi bi-x-lg" aria-hidden="true"></i>'
            : '<i class="bi bi-list" aria-hidden="true"></i>';
    }

    sidebarToggle.addEventListener('click', function () {
        setSidebarOpen(!document.body.classList.contains('sidebar-open'));
    });
    sidebarBackdrop.addEventListener('click', function () {
        setSidebarOpen(false);
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            setSidebarOpen(false);
        }
    });

    if (window.matchMedia('(max-width: 800px)').matches) {
        setSidebarOpen(true);
    }
});