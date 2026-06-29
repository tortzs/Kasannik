<?php
/** @var array $user */
?>
<div class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon"><i class="fa-solid fa-user-gear"></i></div>
        <div>
            <h1>Mój Profil</h1>
            <p>Zarządzaj swoimi danymi i ustawieniami konta</p>
        </div>
    </div>
</div>

<div class="form-card" style="max-width: 800px;">
    <div class="form-header">
        <h2>Dane konta</h2>
    </div>
    
    <div class="form-body">
        <form method="post" id="profile-edit-form" action="/user/update" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div class="form-grid">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 10px;">
                        <div class="avatar" style="width: 80px; height: 80px; font-size: 2rem; background-color: var(--sidebar-bg); color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; overflow: hidden;"">
                            <?php if (!empty($_SESSION['avatar'])): ?>
                                <img src="/uploads/avatars/<?= htmlspecialchars($_SESSION['avatar']) ?>" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                <i class="fa-solid fa-user"></i>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h3 style="margin: 0; color: var(--text-dark); font-size: 1.4rem;">
                                <?php echo htmlspecialchars($user['Username'] ?? 'Użytkownik'); ?>
                            </h3>
                            <?php if (!empty($user['CreatedAt'])): ?>
                                <p style="margin: 5px 0 0; color: var(--text-gray); font-size: 0.9rem;">
                                    Dołączył(a): <?php echo htmlspecialchars(date('d.m.Y', strtotime($user['CreatedAt']))); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            <label>Zmień avatar (tylko JPG/PNG)</label>
                            <input class="form-control" type="file" name="avatar" accept="image/jpeg, image/png">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nazwa użytkownika</label>
                    <input class="form-control" type="text" name="username" 
                           placeholder="<?php echo htmlspecialchars($user['Username'] ?? 'Wpisz nazwę'); ?>"
                           value="<?php echo htmlspecialchars($user['Username'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label>Adres e-mail</label>
                    <input class="form-control" type="email" name="email" 
                           placeholder="<?php echo htmlspecialchars($user['Email'] ?? 'adres@email.com'); ?>"
                           value="<?php echo htmlspecialchars($user['Email'] ?? ''); ?>" required>
                </div>

                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Preferowany motyw</label>
                    <select class="form-control" name="theme_preference">
                        <option value="Light" <?php echo (($user['ThemePreference'] ?? '') === 'Light') ? 'selected' : ''; ?>>Jasny (Light)</option>
                        <option value="Dark" <?php echo (($user['ThemePreference'] ?? '') === 'Dark') ? 'selected' : ''; ?>>Ciemny (Dark)</option>
                    </select>
                </div>
            </div>

            <div class="form-header" style="padding: 30px 0 20px; border-bottom: none; border-top: 1px solid var(--border-color-light); margin-top: 20px;">
                <h2>Zmiana hasła</h2>
                <p style="font-size: 0.85rem; color: var(--text-gray); margin-top: 5px;">Pozostaw puste, jeśli nie chcesz zmieniać hasła.</p>
            </div>

            <div class="form-grid">
                <div class="form-group">
                    <label>Nowe hasło</label>
                    <input class="form-control" type="password" name="new_password" placeholder="Wpisz nowe hasło">
                </div>

                <div class="form-group">
                    <label>Potwierdź nowe hasło</label>
                    <input class="form-control" type="password" name="new_password_confirm" placeholder="Powtórz nowe hasło">
                </div>
            </div>

            <div class="form-actions" style="margin-top: 30px; padding: 0;">
                <button type="submit" class="btn-primary">
                    <i class="fa-solid fa-save"></i> Zapisz zmiany
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const profileForm = document.querySelector('#profile-edit-form');
        if (!profileForm) return;

        let isSubmitting = false;

        profileForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;

            const submitButton = profileForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Zapisywanie...';

            const formData = new FormData(profileForm);

            // Sprawdzenie czy nowe hasła się zgadzają
            const newPassword = formData.get('new_password');
            const newPasswordConfirm = formData.get('new_password_confirm');

            if (newPassword !== newPasswordConfirm) {
                alert('Podane nowe hasła nie są identyczne!');
                isSubmitting = false;
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
                return;
            }

            fetch(profileForm.action, {
                method: 'POST',
                body: formData
            })
            .then(async response => {
                const text = await response.text();
                try {
                    const data = JSON.parse(text);
                    if (!response.ok && !data.success) throw new Error(data.message || 'Błąd serwera');
                    return data;
                } catch (err) {
                    console.error("Odpowiedź PHP:", text);
                    throw new Error("Błąd serwera PHP (sprawdź konsolę)");
                }
            })
            .then(data => {
                if (!data.success) {
                    alert(data.message || 'Wystąpił błąd podczas aktualizacji profilu.');
                    return;
                }
                
                alert('Profil został pomyślnie zaktualizowany!');
                
                // Odświeżenie strony po sukcesie
                window.location.reload();
            })
            .catch(error => alert(error.message || 'Wystąpił błąd połączenia z serwerem.'))
            .finally(() => {
                isSubmitting = false;
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            });
        });
    });
</script>
</div>