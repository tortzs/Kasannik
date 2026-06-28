<div class="main-content">
    <div class="page-header">
        <div class="header-title">
            <div class="title-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
            <div>
                <h1>Prowadzący</h1>
                <p>Edytuj informacje o prowadzącym.</p>
            </div>
        </div>
    </div>

    <div class="form-card">
        <div class="form-header">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
            <h2>Edytuj prowadzącego</h2>
        </div>

        <form method="post" id="instructor-edit-form" class="custom-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="instructorId" value="<?= (int)$instructor['ID'] ?>">

            <div class="form-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Tytuł</label>
                        <select name="academic_title" class="form-control">
                            <?php 
                            $titles = ['mgr', 'mgr inż.', 'dr', 'dr inż.', 'dr hab.', 'prof. dr hab.'];
                            $currentTitle = $instructor['AcademicTitle'] ?? '';
                            ?>
                            <option value="" disabled <?php echo empty($currentTitle) ? 'selected' : ''; ?>>Wybierz tytuł...</option>
                            <?php foreach($titles as $t): ?>
                                <option value="<?php echo $t; ?>" <?php echo ($currentTitle === $t) ? 'selected' : ''; ?>><?php echo $t; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Imię</label>
                        <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($instructor['FirstName'] ?? ''); ?>" placeholder="Wpisz imię..." required>
                    </div>

                    <div class="form-group">
                        <label>Nazwisko</label>
                        <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($instructor['LastName'] ?? ''); ?>" placeholder="Wpisz nazwisko..." required>
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($instructor['Email'] ?? ''); ?>" placeholder="Wpisz adres e-mail...">
                    </div>

                    <div class="form-group">
                        <label>Pokój</label>
                        <input type="text" name="room" class="form-control" value="<?php echo htmlspecialchars($instructor['Room'] ?? ''); ?>" placeholder="Wpisz numer pokoju...">
                        <span class="input-help">Np. A.112, B.205, C.301</span>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <a href="/instructor" class="btn-secondary">Anuluj</a>
                
                <button type="submit" name="action" value="save" class="btn-primary">
                    Zapisz zmiany
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const instructorForm = document.querySelector('#instructor-edit-form');

        if (!instructorForm) return;

        let isSubmitting = false;

        instructorForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;

            const submitButton = e.submitter;
            const submitButtons = instructorForm.querySelectorAll('button[type="submit"]');

            if (!submitButton) {
                isSubmitting = false;
                return;
            }

            const originalButtonText = submitButton.textContent;

            // Zablokowanie przycisków na czas requestu
            submitButtons.forEach(function (button) {
                button.disabled = true;
                button.style.opacity = '0.7';
            });

            submitButton.textContent = 'Zapisywanie...';

            const formData = new FormData(instructorForm);

            if (submitButton.name) {
                formData.append(submitButton.name, submitButton.value);
            }

            fetch('/instructor/update', {
                method: 'POST',
                body: formData
            })
                .then(function (response) {
                    return response.json();
                })
                .then(function (data) {
                    if (!data.success) {
                        alert(data.message);
                        return;
                    }

                    // Po pomyślnej edycji powrót do listy prowadzących
                    window.location.href = '/instructor';
                })
                .catch(function () {
                    alert('Wystąpił błąd połączenia');
                })
                .finally(function () {
                    isSubmitting = false;

                    submitButtons.forEach(function (button) {
                        button.disabled = false;
                        button.style.opacity = '1';
                    });

                    submitButton.textContent = originalButtonText;
                });
        });
    });
</script>