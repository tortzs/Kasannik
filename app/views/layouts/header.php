<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
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
            <a href="/user/logout" class="logout-btn">
                <i class="fa-solid fa-arrow-right-from-bracket logout-link"></i> Wyloguj się
            </a>
            <script>
                document.querySelector('.logout-link').addEventListener('click', function(e) {
                    sessionStorage.clear();
                    localStorage.clear();
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
            <div class="deco-number">01</div>
            <div class="deco-text">HATSUNE<br>MIKU</div>
        </div>
    </aside>