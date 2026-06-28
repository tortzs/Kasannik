<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login-wrapper">
        <div class="logo-container">
            <h1>Kasannik<span class="logo-badge">01</span></h1>
        </div>

        <form method="post" id="login-form">
            <h2>LOGOWANIE</h2>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" id="password" placeholder="Hasło" required>
                <i class="fa-solid fa-eye-slash toggle-password"></i>
            </div>

            <button type="submit" name="register_submit">Zaloguj</button>

            <div class="form-footer">
                Nie posiadasz konta? <a href="/register">Zarejestruj się</a>
            </div>
        </form>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePassword = document.querySelector('.toggle-password');
            const passwordInput = document.querySelector('#password');

            if (togglePassword && passwordInput) {
                togglePassword.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            }

            const loginForm = document.querySelector('#login-form');
            if (!loginForm) return;

            loginForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const submitButton = loginForm.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Logowanie...';

                const formData = new FormData(loginForm);

                fetch('/auth/login', {
                    method: 'POST',
                    body: formData
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        if (data.success) {
                            window.location.href = '/';
                            return;
                        }
                        alert(data.message);
                    })
                    .catch(function () {
                        alert('Wystąpił błąd połączenia');
                    })
                    .finally(function () {
                        submitButton.disabled = false;
                        submitButton.textContent = 'Zaloguj';
                    });
            });
        });
    </script>
</body>
</html>