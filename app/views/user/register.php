<form method="post" id="register-form">
    <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
    >

    <input type="text" name="username" placeholder="Login" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Hasło" required>
    <input type="password" name="password_repeat" placeholder="Powtórz hasło" required>

    <button type="submit" name="register_submit">
        Zarejestruj
    </button>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const registerForm = document.querySelector('#register-form');

        if (!registerForm) return;

        registerForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const submitButton = registerForm.querySelector('button[type="submit"]');

            submitButton.disabled = true;
            submitButton.textContent = 'Rejestrowanie...';

            const formData = new FormData(registerForm);

            fetch('/auth/register', {
                method: 'POST',
                body: formData
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    console.log(data);

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