<?php
/* @var array $semester */
?>
<div class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon"><i class="fa-solid fa-pen-to-square"></i></div>
        <div>
            <h1>Edytuj semestr</h1>
            <p>Edytujesz: <strong><?php echo htmlspecialchars($semester['Name'] ?? ''); ?></strong></p>
        </div>
    </div>
</div>

<div class="form-card" style="max-width: 800px;">
    <div class="form-header">
        <h2>Dane semestru</h2>
    </div>
    
    <div class="form-body">
        <form method="post" id="semester-edit-form">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
            <input type="hidden" name="semesterId" value="<?= htmlspecialchars($semester['ID'] ?? '') ?>">

            <div class="form-grid">
                <div class="form-group" style="grid-column: 1 / -1;">
                    <label>Nazwa semestru</label>
                    <input class="form-control" type="text" name="name" value="<?php echo htmlspecialchars($semester['Name'] ?? ''); ?>" placeholder="Nazwa semestru" required>
                </div>

                <div class="form-group">
                    <label>Data rozpoczęcia</label>
                    <input class="form-control" type="date" name="start_date" value="<?php echo htmlspecialchars($semester['StartDate'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label>Data zakończenia</label>
                    <input class="form-control" type="date" name="end_date" value="<?php echo htmlspecialchars($semester['EndDate'] ?? ''); ?>" required>
                </div>
                <div class="form-group" style="margin-top: 15px; margin-bottom: 15px;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_current" value="1"
                                <?php echo (($semester['IsCurrent'] ?? 0) == 1) ? 'checked' : ''; ?>
                               style="transform: scale(1.2);">
                        <span style="font-weight: bold;">Ustaw ten semestr jako aktywny</span>
                    </label>
                </div>
            </div>

            <div class="form-actions" style="margin-top: 30px; padding: 0;">
                <a href="/semester" class="btn-secondary" style="margin-right: auto;">Anuluj</a>
                <button type="submit" name="action" value="save" class="btn-primary">
                    <i class="fa-solid fa-check"></i> Zapisz zmiany
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const semesterForm = document.querySelector('#semester-edit-form');
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
            submitButton.textContent = 'Edytowanie...';

            const formData = new FormData(semesterForm);
            if (submitButton.name) formData.append(submitButton.name, submitButton.value);

            fetch('/semester/update', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message);
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