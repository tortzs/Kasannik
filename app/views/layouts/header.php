<div class="mobile-overlay" id="mobile-overlay"></div>
<button class="mobile-menu-btn" id="mobile-menu-btn">
    <i class="fa-solid fa-bars"></i>
</button>
<aside class="sidebar">
    <a href="/dashboard" class="sidebar-logo">
        <span class="logo-badge">01</span>
        <div class="logo-text">kasannik<span>カサニック</span></div>
    </a>

    <div class="sidebar-profile-container">
        <a href="/user" class="profile-card">
            <?php if (!empty($_SESSION['avatar'])): ?>
                <img src="/uploads/avatars/<?= htmlspecialchars($_SESSION['avatar']) ?>" alt="Avatar" class="avatar">
            <?php else: ?>
                <i class="fa-solid fa-user"></i>
            <?php endif; ?>
            <div class="profile-name"><?php echo htmlspecialchars($_SESSION['username'] ?? 'Użytkownik'); ?></div>
            <div class="profile-role">Student</div>
            <span class="semester-badge"><?php echo htmlspecialchars($_SESSION['active_semester_name'] ?? 'Brak aktywnego semestru'); ?></span>
        </a>
        <div style="display: flex; gap: 10px; margin-top: 15px; width: 100%; padding: 0 15px; box-sizing: border-box;">
            <a href="/user/logout" class="logout-btn" style="margin-top: 0; flex: 1; justify-content: center;">
                <i class="fa-solid fa-arrow-right-from-bracket logout-link"></i> Wyloguj
            </a>
            <button id="sidebar-theme-toggle" class="sidebar-theme-toggle-btn">
                <i class="fa-solid fa-circle-half-stroke"></i>
            </button>
        </div>
        <script>
            document.querySelector('.logout-link').addEventListener('click', function(e) {
                const currentTheme = localStorage.getItem('kasannik-theme');
                sessionStorage.clear();
                localStorage.clear();
                if (currentTheme) {
                    localStorage.setItem('kasannik-theme', currentTheme);
                }
            });
            document.getElementById('sidebar-theme-toggle').addEventListener('click', function() {
                const body = document.body;
                body.classList.toggle('dark-theme');

                const isDark = body.classList.contains('dark-theme');
                const newTheme = isDark ? 'dark' : 'light';
                localStorage.setItem('kasannik-theme', newTheme);

                fetch('/user/updateTheme', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ theme: newTheme })
                }).catch(err => console.error('Błąd zapisu motywu w bazie:', err));
            });
        </script>
    </div>

    <nav class="sidebar-nav">
        <a href="/instructor"><i class="fa-regular fa-user"></i> Prowadzący</a>
        <a href="/semester"><i class="fa-regular fa-calendar"></i> Semestr</a>
        <a href="/schedule/deadlines"><i class="fa-solid fa-clipboard-list"></i> Terminy</a>
        <a href="/schedule"><i class="fa-regular fa-calendar-days"></i> Plan Zajęć</a>
        <a href="/semester/active"><i class="fa-solid fa-book"></i> Przedmioty</a>
        <a href="/todo"><i class="fa-regular fa-square-check"></i> Do zrobienia</a>
    </nav>

    <div class="sidebar-footer-deco">
        <div class="deco-miku">
            <div class="deco-number">01</div>
            <div class="deco-text">HATSUNE<br>MIKU</div>
        </div>
        <div class="deco-teto">
            <div class="deco-number">0401</div>
            <div class="deco-text">KASANE<br>TETO</div>
        </div>
    </div>
</aside>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuBtn = document.getElementById('mobile-menu-btn');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('mobile-overlay');

        function toggleMenu() {
            sidebar.classList.toggle('sidebar-active');
            overlay.classList.toggle('active');
        }

        if (menuBtn) {
            menuBtn.addEventListener('click', toggleMenu);
            overlay.addEventListener('click', toggleMenu);
        }
    });
</script>