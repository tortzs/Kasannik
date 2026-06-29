<?php
/** @var Int $userId */
?>
<div class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon"><i class="fa-solid fa-calendar-plus"></i></div>
        <div>
            <h1>Dodaj semestr</h1>
            <p>Wypełnij formularz, aby utworzyć nowy semestr</p>
        </div>
    </div>
</div>

<div class="form-card" style="max-width: 800px;">
    <div class="form-header">
        <h2>Dane semestru</h2>
    </div>
    
    <div class="form-body">
        <form method="post" id="semester-add-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div class="form-grid">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Nazwa semestru</label>
                    <input class="form-control" type="text" name="name" placeholder="np. Semestr 4 - Letni" required>
                </div>

                <div class="form-group">
                    <label>Data rozpoczęcia</label>
                    <input class="form-control" type="date" name="start_date" required>
                </div>

                <div class="form-group">
                    <label>Data zakończenia</label>
                    <input class="form-control" type="date" name="end_date" required>
                </div>
            </div>

            <div class="form-actions" style="margin-top: 30px; padding: 0;">
                <button type="submit" name="action" value="save" class="btn-primary">
                    <i class="fa-solid fa-check"></i> Zapisz
                </button>
                <button type="submit" name="action" value="save_and_new" class="btn-secondary">
                    Zapisz i dodaj kolejny
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const semesterForm = document.querySelector('#semester-add-form');
        if (!semesterForm) return;

        let isSubmitting = false;

        semesterForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;

            const submitButton = e.submitter;
            const submitButtons = semesterForm.querySelectorAll('button[type="submit"]');

            if (!submitButton) {
                isSubmitting = false;
                return;
            }

            const originalButtonText = submitButton.innerHTML;
            submitButtons.forEach(button => button.disabled = true);
            submitButton.textContent = 'Zapisywanie...';

            const formData = new FormData(semesterForm);
            if (submitButton.name) formData.append(submitButton.name, submitButton.value);

            fetch('/semester/insert', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message);
                    return;
                }

                if (data.action === 'save_and_new') {
                    semesterForm.reset();
                    const firstInput = semesterForm.querySelector('input, select, textarea');
                    if (firstInput) firstInput.focus();
                    return;
                }

                window.location.href = '/semester';
            })
            .catch(() => alert('Wystąpił błąd połączenia'))
            .finally(() => {
                isSubmitting = false;
                submitButtons.forEach(button => button.disabled = false);
                submitButton.innerHTML = originalButtonText;
            });
        });
    });
</script>
</div>