<form method="post" id="login-form">
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
    >

    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Hasło" required>
    <button type="submit" name="register_submit">
        Zaloguj
    </button>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
                    submitButton.textContent = 'Zarejestruj';
                });
        });
    });
</script>