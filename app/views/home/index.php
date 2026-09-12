<link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/landing.css">

<div class="landing-wrapper" id="app-wrapper">
    <script>
        (function() {
            const savedTheme = localStorage.getItem('kasannik-theme');
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (savedTheme === 'dark' || (!savedTheme && systemPrefersDark)) {
                document.getElementById('app-wrapper').classList.add('dark-theme');
            }
        })();
    </script>
    <div class="landing-container">
        <nav class="navbar">
            <div class="nav-profile-group">
                <button class="icon-btn btn-profile">K</button>
            </div>

            <div class="nav-links">
                <a href="#about" class="active">O projekcie</a>
                <a href="#features">Funkcje</a>
                <a href="#aboutus">O nas</a>
            </div>

            <div class="nav-auth-group">
                <button id="theme-toggle" class="btn-rounded btn-theme icon-btn-theme" aria-label="Przełącz motyw"><i class="fa-solid fa-moon"></i></button>
                <a href="/login" class="btn-rounded btn-outline">Zaloguj się</a>
                <a href="/register" class="btn-rounded btn-solid">Zarejestruj się</a>
            </div>
        </nav>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sections = document.querySelectorAll('header[id], section[id]');
                const navLinks = document.querySelectorAll('.nav-links a');

                window.addEventListener('scroll', () => {
                    let current = 'about';

                    sections.forEach(section => {
                        const rect = section.getBoundingClientRect();
                        if (rect.top <= 300) {
                            current = section.getAttribute('id');
                        }
                    });

                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#' + current) {
                            link.classList.add('active');
                        }
                    });
                });
            });
        </script>
        <header class="hero-banner" id="about">
            <div class="hero-content">
                <img src="/assets/images/logo.png" alt="Logo" class="hero-logo" onerror="this.style.display='none'">

                <h1>Kasannik</h1>
                <h3>Twój osobisty, bezpieczny asystent akademicki.</h3>
                <p>Zarządzaj planem zajęć, śledź postępy i nigdy więcej nie przegap deadline'u.
                Błyskawiczny dostęp do planu zajęć, ocen i projektów. Gotowy na nowy semestr?</p>

                <a href="/register" class="start-button">Zacznij już teraz!</a>
            </div>

            <div class="hero-character-container">
                <img src="/assets/images/miku-landingpage.png" alt="Miku" class="hero-character miku-char">
                <img src="/assets/images/teto-landingpage.webp" alt="Teto" class="hero-character teto-char" onerror="this.style.display='none'">
            </div>
        </header>
        <main class="dashboard-grid">
            <section id="features">
                <h2 class="section-title">Najważniejsze funkcje &gt;</h2>
                <div class="features-grid">
                    <div class="features-card">
                        <h3>Wszystko pod ręką</h3>
                        <p>Plan zajęć, linki do USOSa i Moodle, adresy e-mail do prowadzących - Wszystko w jednym miejscu!</p>
                    </div>
                    <div class="features-card">
                        <h3>Przypomnienia o deadlineach</h3>
                        <p>Wszystkie terminy kolokwiów i oddawania projektów w jednym miejscu - posegregowane według najbliższej daty!</p>
                    </div>
                    <div class="features-card">
                        <h3>Przejrzystość</h3>
                        <p>Pełny kod źródłowy aplikacji dostępny jest na GitHub! Nie zbieramy logów o użytkownikach.</p>
                    </div>
                </div>
            </section>
            <section>
                <h2 class="section-title">Moduły</h2>
                <div class="modules-list">
                    <div class="module-item">&gt; Plan zajęć</div>
                    <div class="module-item">&gt; Spis semestrów</div>
                    <div class="module-item">&gt; Lista prowadzących</div>
                    <div class="module-item">&gt; Przedmioty</div>
                    <div class="module-item">&gt; Zadania i zaliczenia</div>
                    <div class="module-item">&gt; Najbliższe terminy</div>
                    <div class="module-item">&gt; Lista To-Do</div>
                </div>
            </section>
            <section>
                <h2 class="section-title">Jak zacząć?</h2>
                <div class="info-list">
                    <div class="info-card">
                        <div class="info-number">01</div>
                        <div class="info-text">
                            <h3>Utwórz konto</h3>
                            <p>Zarejestruj się w kilka sekund. Bezpieczne logowanie chroni Twoje dane i plany zajęć.</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-number">02</div>
                        <div class="info-text">
                            <h3>Dodaj przedmioty</h3>
                            <p>Skonfiguruj swój semestr. Dodaj prowadzących, podepnij linki do USOSa, Moodle'a i Teamsów.</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="info-number">03</div>
                        <div class="info-text">
                            <h3>Kontroluj chaos</h3>
                            <p>Dodawaj zadania, sprawdzaj terminy kolokwiów i ciesz się spokojem przez całą sesję.</p>
                        </div>
                    </div>
                </div>
            </section>
            <h2 class="author-section-name">O nas</h2>
            <section id="aboutus" class="authors-section">
                <div class="author-card maintainer-card">
                    <h3 class="author-title">Project Maintainer</h3>

                    <div class="author-profile main-author">
                        <div class="avatar-large" style="background-image: url('https://github.com/zheyurii.png');"></div>

                        <div class="author-details">
                            <h4>zheYurii</h4>
                            <span class="role-badge cyan-badge">Lead Developer</span>
                            <p>Rozwijam logikę biznesową, dbam o cyberbezpieczeństwo oraz infrastrukturę serwerową. Pilnuję, żeby Kasannik działał szybko i stabilnie.</p>
                            <p>Obecnie prowadzę ten projekt w pojedynkę - poprawiam błędy, dodaję nowe funkcje i pracuję nad nowym stylem (czego przykładem jest ta strona).</p>
                            <p>A przy okazji tworzę coś, co przyda się nam wszystkim :3</p>
                            <a href="https://zheyurii.xyz" target="_blank" class="author-button">
                                Zobacz moje pozostałe projekty →
                            </a>
                        </div>
                    </div>
                </div>
                <div class="author-card crew-card">
                    <h3 class="author-title">Projekt nigdy by nie powstał bez ich pomocy:</h3>
                    <div class="author-profile side-author">
                        <div class="avatar-medium" style="background-image: url('https://github.com/tortzs.png');"></div>
                        <div class="author-details">
                            <h4>tortzsu</h4>
                            <span class="role-badge pink-badge">System Architect</span>
                            <p>Zaprojektował fundamenty aplikacji i autorski system routingu MVC. Zbudował solidną bazę architektoniczną, na której do dziś opiera się cały silnik Kasannika.</p>
                        </div>
                    </div>
                    <div class="author-profile side-author">
                        <div class="avatar-medium" style="background-image: url('https://github.com/idex04.png');"></div>
                        <div class="author-details">
                            <h4>idex</h4>
                            <span class="role-badge purple-badge">UI/UX Designer</span>
                            <p>Stworzył fundamenty warstwy wizualnej głównego systemu aplikacji, na której do dziś opiera się ten nowoczesny, przejrzysty styl, a korzystanie z niego to czysta przyjemność.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <footer class="landing-footer">
            <div class="footer-content">
                <span class="footer-brand">Kasannik &copy; 2026 - <a href="/terms">Polityka prywatności i regulamin</a></span>
                <span class="footer-motto">Wielbmy Teto i jedzmy bagietki 🥖</span>
            </div>
        </footer>
        <script>
            const wrapper = document.querySelector('.landing-wrapper');
            const toggleBtn = document.getElementById('theme-toggle');

            document.addEventListener('DOMContentLoaded', () => {
                const isDark = wrapper.classList.contains('dark-theme');
                const icon = toggleBtn.querySelector('i');
                if (icon && isDark) {
                    icon.classList.replace('fa-moon', 'fa-sun');
                }
            });

            toggleBtn.addEventListener('click', () => {
                wrapper.classList.toggle('dark-theme');
                const isDark = wrapper.classList.contains('dark-theme');
                localStorage.setItem('kasannik-theme', isDark ? 'dark' : 'light');
                const icon = toggleBtn.querySelector('i');
                if (icon) {
                    if (isDark) {
                        icon.classList.replace('fa-moon', 'fa-sun');
                    } else {
                        icon.classList.replace('fa-sun', 'fa-moon');
                    }
                }
            });
        </script>
    </div>
</div>