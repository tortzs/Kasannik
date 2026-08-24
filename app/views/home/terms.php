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
        <a href="/" style="color: var(--accent-color); text-decoration: none; font-weight: bold;">&larr; Wróć do strony głównej</a>

        <div class="panel" style="margin-top: 2rem; padding: 3rem; background: var(--panel-bg); border-radius: var(--radius-lg);">
            <h1 style="color: var(--accent-color); margin-bottom: 2rem;">Regulamin i Polityka Prywatności Kasannika</h1>

            <p style="margin-bottom: 0.5rem; line-height: 1.6;"><strong>1. Czym jest Kasannik?</strong><br>
                Kasannik to niekomercyjny, darmowy projekt studencki i element portfolio programistycznego. Aplikacja służy do organizacji czasu i materiałów akademickich. Korzystając z niej, zgadzasz się na poniższe zasady (nie martw się, są krótkie i ludzkie).</p><br>

            <p style="margin-bottom: 0.5rem; line-height: 1.6;"><strong>2. Twoje dane i Prywatność</strong><br>
                <ul style="list-style: none; padding-left: 0; margin-top: 0.5rem; margin-bottom: 2rem;">
                    <li style="margin-bottom: 0.8rem; padding-left: 1.5rem; position: relative; line-height: 1.6;">
                        <span style="position: absolute; left: 0; color: var(--accent-color); font-weight: bold; font-size: 1.2rem;">&bull;</span>
                        Co zbieramy: W bazie zapisujemy wyłącznie Twój adres e-mail (niezbędny do logowania i odzyskiwania hasła) oraz dane, które sam wprowadzisz do systemu (np. nazwy przedmiotów, nazwiska prowadzących, Twoje notatki).
                    </li>
                    <li style="margin-bottom: 0.8rem; padding-left: 1.5rem; position: relative; line-height: 1.6;">
                        <span style="position: absolute; left: 0; color: var(--accent-color); font-weight: bold; font-size: 1.2rem;">&bull;</span>
                        Czego NIE zbieramy: Nie śledzimy Twojej aktywności wewnątrz aplikacji, nie sprzedajemy Twoich danych reklamodawcom i nie logujemy Twoich adresów IP w naszej bazie danych.
                    </li>
                    <li style="margin-bottom: 0.8rem; padding-left: 1.5rem; position: relative; line-height: 1.6;">
                        <span style="position: absolute; left: 0; color: var(--accent-color); font-weight: bold; font-size: 1.2rem;">&bull;</span>
                        Bezpieczeństwo: Twoje hasło jest bezpiecznie zahashowane. Ruch sieciowy przechodzi przez serwery Cloudflare, które automatycznie dbają o bezpieczeństwo i mogą tymczasowo przetwarzać Twój adres IP w celu ochrony aplikacji przed atakami.
                    </li>
                    <li style="margin-bottom: 0.8rem; padding-left: 1.5rem; position: relative; line-height: 1.6;">
                        <span style="position: absolute; left: 0; color: var(--accent-color); font-weight: bold; font-size: 1.2rem;">&bull;</span>
                        Usuwanie konta: W każdej chwili masz prawo do bycia zapomnianym. Chcesz usunąć konto i wszystkie swoje dane? Daj znać administratorowi.</p><br>
                    </li>
                </ul>
            <p style="margin-bottom: 0.5rem; line-height: 1.6;"><strong>3. Zasada "As-Is"</strong><br>
                Ponieważ Kasannik to projekt hobbystyczny rozwijany w pojedynkę, aplikacja jest udostępniana w stanie "takim, jakim jest" (as-is).</p>
                <ul style="list-style: none; padding-left: 0; margin-top: 0.5rem; margin-bottom: 2rem;">
                    <li style="margin-bottom: 0.8rem; padding-left: 1.5rem; position: relative; line-height: 1.6;">
                        <span style="position: absolute; left: 0; color: var(--accent-color); font-weight: bold; font-size: 1.2rem;">&bull;</span>
                        Nie gwarantuję 100% niezawodności ani działania 24/7.
                    </li>
                    <li style="margin-bottom: 0.8rem; padding-left: 1.5rem; position: relative; line-height: 1.6;">
                        <span style="position: absolute; left: 0; color: var(--accent-color); font-weight: bold; font-size: 1.2rem;">&bull;</span>
                        Nie ponoszę odpowiedzialności za utratę danych. Gorąco polecam wszelkie dane (plany zajęć, notatki, terminy kolokwiów itp.) trzymać też w innych miejscach, aby w razie utraty dostępu do Kasannika nie utracić danych!
                    </li>
                </ul>
            <p style="margin-bottom: 0.5rem; line-height: 1.6;"><strong>4. Zasady gry</strong><br>
                Traktujmy się z szacunkiem. Próby celowego psucia aplikacji, wstrzykiwania złośliwego kodu lub obciążania serwera będą skutkować natychmiastowym i permanentnym zablokowaniem konta oraz dostępu.</p><br>

            <p style="margin-bottom: 0.5rem; line-height: 1.6;"><strong>5. Kontakt</strong><br>
                Projekt utrzymuje i rozwija zheYurii. W razie problemów technicznych, znalezionych błędów lub pytań, kontaktuj się bezpośrednio ze mną.</p>
            <ul style="list-style: none; padding-left: 0; margin-top: 0.5rem; margin-bottom: 2rem;">
                <li style="margin-bottom: 0.8rem; padding-left: 1.5rem; position: relative; line-height: 1.6;">
                    <span style="position: absolute; left: 0; color: var(--accent-color); font-weight: bold; font-size: 1.2rem;">&bull;</span>
                    Strona: <a href="https://zheyurii.xyz">https://zheyurii.xyz</a>
                </li>
                <li style="margin-bottom: 0.8rem; padding-left: 1.5rem; position: relative; line-height: 1.6;">
                    <span style="position: absolute; left: 0; color: var(--accent-color); font-weight: bold; font-size: 1.2rem;">&bull;</span>
                    E-mail: mateusz@zheyurii.xyz
                </li>
                <li style="margin-bottom: 0.8rem; padding-left: 1.5rem; position: relative; line-height: 1.6;">
                    <span style="position: absolute; left: 0; color: var(--accent-color); font-weight: bold; font-size: 1.2rem;">&bull;</span>
                    Discord: zheyurii
                </li>
            </ul>
        </div>

    </div>
</div>