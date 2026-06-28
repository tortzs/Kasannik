<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-wrapper">
        <div class="logo-container">
            <h1>Kasannik<span class="logo-badge">01</span></h1>
        </div>

        <form method="post" id="register-form">
            <h2>REJESTRACJA</h2>
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input type="text" name="username" placeholder="Login" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" placeholder="Hasło" required>
                <i class="fa-solid fa-eye-slash toggle-password"></i>
            </div>
            
            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password_confirm" placeholder="Powtórz hasło" required>
                <i class="fa-solid fa-eye-slash toggle-password"></i>
            </div>

            <button type="submit" name="register_submit">Zarejestruj</button>

            <div class="form-footer">
                Posiadasz już konto? <a href="/login">Zaloguj się</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const togglePasswords = document.querySelectorAll('.toggle-password');
            
            togglePasswords.forEach(function(toggle) {
                toggle.addEventListener('click', function () {
                    const input = this.previousElementSibling;
                    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
                    input.setAttribute('type', type);
                    this.classList.toggle('fa-eye');
                    this.classList.toggle('fa-eye-slash');
                });
            });

            const registerForm = document.querySelector('#register-form');
            if (!registerForm) return;

            registerForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const submitButton = registerForm.querySelector('button[type="submit"]');
                submitButton.disabled = true;
                submitButton.textContent = 'Rejestracja...';

                const formData = new FormData(registerForm);

                fetch('/auth/register', {
                    method: 'POST',
                    body: formData
                })
                    .then(function (response) {
                        return response.json();
                    })
                    .then(function (data) {
                        if (data.success) {
                            window.location.href = '/login';
                            return;
                        }
                        alert(data.message);
                    })
                    .catch(function () {
                        alert('Wystąpił błąd połączenia');
                    })
                    .finally(function () {
                        submitButton.disabled = false;
                        submitButton.textContent = 'Zarejestruj';
                    });
            });
        });
    </script>
</body>
</html>