document.addEventListener('DOMContentLoaded', function () {
    var sidebar = document.getElementById('sidebar');
    var toggle = document.querySelector('[data-bs-toggle="offcanvas"][data-bs-target="#sidebar"]');
    if (!sidebar) return;

    var mobileNav = window.matchMedia('(max-width: 991.98px)');
    var restoreSidebar = mobileNav.matches && sessionStorage.getItem('eduspaceSidebarOpen') === 'true';
    sessionStorage.removeItem('eduspaceSidebarOpen');

    if (toggle) {
        toggle.setAttribute('aria-controls', 'sidebar');
        toggle.setAttribute('aria-expanded', restoreSidebar ? 'true' : 'false');
        toggle.setAttribute('aria-label', 'Open admin navigation');
    }

    function updateToggle(isOpen) {
        if (!toggle) return;
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        toggle.setAttribute('aria-label', isOpen ? 'Close admin navigation' : 'Open admin navigation');
    }

    sidebar.addEventListener('shown.bs.offcanvas', function () { updateToggle(true); });
    sidebar.addEventListener('hidden.bs.offcanvas', function () { updateToggle(false); });

    sidebar.querySelectorAll('.sidebar-nav a').forEach(function (link) {
        link.addEventListener('click', function () {
            if (mobileNav.matches && /\/(admin|crud)\//.test(new URL(link.href).pathname)) {
                sessionStorage.setItem('eduspaceSidebarOpen', 'true');
            }
        });
    });

    if (restoreSidebar && window.bootstrap) {
        window.bootstrap.Offcanvas.getOrCreateInstance(sidebar).show();
    }
});
