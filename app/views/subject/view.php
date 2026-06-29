<?php
?>
<div class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon"><i class="fa-solid fa-book-bookmark"></i></div>
        <div>
            <h1><?php echo htmlspecialchars($subject['Name'] ?? 'Szczegóły przedmiotu'); ?></h1>
            <p>Zarządzaj swoimi zadaniami, projektami i ocenami</p>
        </div>
    </div>
    <a href="/semester/view/<?php echo (int)($subject['SemesterID'] ?? 0); ?>" class="btn-secondary">
        <i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i> Wróć do semestru
    </a>
</div>

<div class="dashboard-grid" style="margin-bottom: 30px;">
    <div class="card full-width" style="flex-direction: row; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
        <div style="display: flex; gap: 40px; flex-wrap: wrap;">
            <div>
                <p class="text-gray" style="font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Punkty ECTS</p>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="avatar-placeholder" style="background-color: var(--color-blue-light); color: var(--color-blue);"><i class="fa-solid fa-graduation-cap"></i></div>
                    <span class="fw-bold text-dark" style="font-size: 1.2rem;"><?php echo (int)($subject['ECTS'] ?? 0); ?></span>
                </div>
            </div>
            <div>
                <p class="text-gray" style="font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Max Punktów</p>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div class="avatar-placeholder" style="background-color: var(--color-pink-light); color: var(--color-pink);"><i class="fa-solid fa-star"></i></div>
                    <span class="fw-bold text-dark" style="font-size: 1.2rem;"><?php echo (int)($subject['MaxPossiblePoints'] ?? 0); ?></span>
                </div>
            </div>
            <div>
                <p class="text-gray" style="font-size: 0.85rem; font-weight: 600; margin-bottom: 5px;">Notatki do przedmiotu</p>
                <p class="text-dark" style="font-size: 0.95rem; margin: 0; max-width: 400px;">
                    <?php echo htmlspecialchars($subject['GeneralNotes'] ?? 'Brak notatek'); ?>
                </p>
            </div>
        </div>
    </div>
</div>

<form id="add-assignment-form" method="post" action="/assignment/insert"></form>

<div class="table-container">
    <div class="table-toolbar">
        <h2><i class="fa-solid fa-list-check text-primary" style="margin-right: 8px;"></i> Zadania i Zaliczenia</h2>
    </div>
    <table class="data-table" id="assignments-table">
        <thead>
        <tr>
            <th style="width: 25%;">Tytuł</th>
            <th>Typ</th>
            <th>Max Pkt</th>
            <th>Zdobyte Pkt</th>
            <th>Status</th>
            <th>Deadline</th>
            <th style="text-align: center;">Akcje</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($assignments)) {
            foreach ($assignments as $assignment) { 
                $isCompleted = (bool)$assignment['IsCompleted'];
                ?>
                <tr>
                    <td class="fw-bold">
                        <span class="open-details-modal" style="cursor: pointer; color: var(--text-dark); transition: color 0.2s;"
                              onmouseover="this.style.color='var(--primary)'" onmouseout="this.style.color='var(--text-dark)'"
                              data-id="<?php echo (int)$assignment['ID']; ?>"
                              data-title="<?php echo htmlspecialchars($assignment['Title']); ?>"
                              data-teammembers="<?php echo htmlspecialchars($assignment['TeamMembers'] ?? ''); ?>"
                              data-notes="<?php echo htmlspecialchars($assignment['Notes'] ?? ''); ?>">
                            <?php echo htmlspecialchars($assignment['Title']); ?>
                        </span>
                    </td>
                    <?php
                    $typeClass = match($assignment['TypeName']) {
                        'Kolokwium' => 'KOLOS',
                        'Projekt Grupowy' => 'PROJ',
                        'Egzamin Pisemny' => 'EGZ',
                        'Zadanie Domowe' => 'ZAD',
                        'Sprawozdanie Laboratoryjne' => 'ZAD',
                        'Projekt Indywidualny' => 'PROJ',
                        default => 'DEF'
                    };
                    ?>
                    <td><span class="badge <?= $typeClass ?>-bg"><?php echo htmlspecialchars($assignment['TypeName']); ?></span></td>
                    <td class="fw-bold"><?php echo htmlspecialchars($assignment['MaxPoints']); ?></td>
                    <td class="text-gray fw-bold"><?php echo $assignment['EarnedPoints'] !== null ? htmlspecialchars($assignment['EarnedPoints']) : '-'; ?></td>
                    <td>
                        <span class="badge <?php echo $isCompleted ? 'badge-teal' : 'badge-pink'; ?>">
                            <?php echo $isCompleted ? '<i class="fa-solid fa-check"></i> Zakończone' : 'Do zrobienia'; ?>
                        </span>
                    </td>
                    <td class="text-gray" style="font-size: 0.9rem;">
                        <?php echo htmlspecialchars($assignment['Deadline']); ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button type="button" class="btn-icon edit open-update-modal" 
                                    data-id="<?php echo (int)$assignment['ID']; ?>"
                                    data-points="<?php echo htmlspecialchars($assignment['EarnedPoints'] ?? ''); ?>"
                                    data-completed="<?php echo (int)$assignment['IsCompleted']; ?>"
                                    title="Szybka aktualizacja punktów">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>

                            <form method="post" action="/assignment/delete" style="margin: 0;" onsubmit="return confirm('Na pewno usunąć zadanie?');">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="assignmentId" value="<?= (int)$assignment['ID'] ?>">
                                <input type="hidden" name="subjectId" value="<?= (int)$subject['ID'] ?>">
                                <button type="submit" class="btn-icon delete"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php
            }
        } else { ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 30px; color: var(--text-gray);">
                    Brak zadań w tym przedmiocie. Dodaj nowe poniżej.
                </td>
            </tr>
        <?php } ?>
        </tbody>
        
        <tfoot style="background-color: var(--sidebar-bg); border-top: 2px solid var(--primary);">
        <tr>
            <td style="padding: 12px 15px;">
                <input class="form-control" form="add-assignment-form" type="text" name="assignment_title" placeholder="Tytuł zadania" required style="padding: 8px 12px;">
            </td>
            <td style="padding: 12px 15px;">
                <select class="form-control" form="add-assignment-form" name="assignment_type" required style="padding: 8px 12px; font-size: 0.85rem;">
                    <?php if(!empty($assignmentTypes)) {
                        foreach ($assignmentTypes as $type) { ?>
                            <option value="<?php echo $type['TypeID'] ?>">
                                <?php echo htmlspecialchars($type['TypeName']) ?>
                            </option>
                        <?php } } ?>
                </select>
            </td>
            <td style="padding: 12px 15px;">
                <input class="form-control" form="add-assignment-form" type="number" min="0" step="0.5" name="assignment_points" placeholder="Max pkt" required style="padding: 8px 12px; width: 80px;">
            </td>
            <td style="padding: 12px 15px;">
                <input class="form-control" form="add-assignment-form" type="number" min="0" step="0.5" name="assignment_earned_points" placeholder="Zdobyte" style="padding: 8px 12px; width: 80px;">
            </td>
            <td style="padding: 12px 15px; vertical-align: middle;">
                <label style="font-size: 0.85rem; font-weight: 600; color: var(--text-dark); cursor: pointer; display: flex; align-items: center; gap: 5px;">
                    <input form="add-assignment-form" type="checkbox" name="assignment_is_completed" value="1" style="width: 16px; height: 16px; accent-color: var(--primary);">
                    Zakończone
                </label>
            </td>
            <td style="padding: 12px 15px;">
                <input class="form-control" form="add-assignment-form" type="datetime-local" name="assignment_deadline" required style="padding: 8px 12px;">
            </td>
            <td style="padding: 12px 15px; text-align: center;">
                <input form="add-assignment-form" type="hidden" name="assignment_subject" value="<?= (int)$subject['ID'] ?>">
                <input form="add-assignment-form" type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                <button class="btn-primary" form="add-assignment-form" type="submit" style="padding: 8px 15px; margin: 0 auto;">
                    <i class="fa-solid fa-plus"></i> Dodaj
                </button>
            </td>
        </tr>
        </tfoot>
    </table>
</div>

<div id="updateModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000; backdrop-filter: blur(3px);">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width: 100%; max-width: 400px; padding: 20px;">
        <div class="form-card" style="margin: 0;">
            <div class="form-header">
                <h2>Aktualizuj punkty i status</h2>
            </div>
            <div class="form-body">
                <form method="post" action="/assignment/update">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="assignmentId" id="modal_assignment_id" value="">
                    <input type="hidden" name="subjectId" value="<?= (int)$subject['ID'] ?>">

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Zdobyte punkty (opcjonalnie):</label>
                        <input class="form-control" type="number" min="0" step="0.5" name="earned_points" id="modal_earned_points" value="">
                    </div>

                    <div class="form-group" style="margin-bottom: 25px;">
                        <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; user-select: none;">
                            <input type="checkbox" name="is_completed" id="modal_is_completed" value="1" style="width: 18px; height: 18px; accent-color: var(--primary);">
                            <span style="font-weight: 600; color: var(--text-dark);">Oznacz zadanie jako ukończone</span>
                        </label>
                    </div>

                    <div class="form-actions" style="padding: 0;">
                        <button type="button" id="closeModalBtn" class="btn-secondary">Anuluj</button>
                        <button type="submit" class="btn-primary"><i class="fa-solid fa-check"></i> Zapisz</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="detailsModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1001; backdrop-filter: blur(3px);">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width: 100%; max-width: 550px; padding: 20px;">
        <div class="form-card" style="margin: 0;">
            <div class="form-header" style="justify-content: space-between;">
                <h2 id="details_modal_title" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 300px;">Szczegóły zadania</h2>
                <button type="button" id="toggleEditBtn" class="btn-secondary" style="padding: 6px 12px; font-size: 0.8rem;">
                    <i class="fa-solid fa-lock" style="margin-right: 5px;"></i> Odblokuj
                </button>
            </div>
            
            <div class="form-body">
                <form method="post" action="/assignment/update-details">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="assignmentId" id="details_modal_assignment_id" value="">
                    <input type="hidden" name="subjectId" value="<?= (int)$subject['ID'] ?>">

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Zespół / Osoby realizujące:</label>
                        <input class="form-control" type="text" name="teammembers" id="details_modal_teammembers" placeholder="Brak przypisanych osób..." readonly style="background-color: var(--sidebar-bg); border-color: transparent;">
                    </div>

                    <div class="form-group" style="margin-bottom: 25px;">
                        <label>Opis, cele i zagadnienia:</label>
                        <textarea class="form-control" name="notes" id="details_modal_notes" placeholder="Brak notatek..." readonly style="min-height:120px; resize: vertical; background-color: var(--sidebar-bg); border-color: transparent;"></textarea>
                    </div>

                    <div class="form-actions" style="padding: 0;">
                        <button type="button" id="closeDetailsModalBtn" class="btn-secondary">Zamknij</button>
                        <button type="submit" id="saveDetailsBtn" class="btn-primary" style="display: none;"><i class="fa-solid fa-save"></i> Zapisz szczegóły</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const updateModal = document.getElementById('updateModal');
        const closeUpdateBtn = document.getElementById('closeModalBtn');

        function attachUpdateModalEvents() {
            document.querySelectorAll('.open-update-modal').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('modal_assignment_id').value = this.dataset.id;
                    document.getElementById('modal_earned_points').value = this.dataset.points;
                    document.getElementById('modal_is_completed').checked = (this.dataset.completed === '1');
                    updateModal.style.display = 'block';
                });
            });
        }
        attachUpdateModalEvents();

        closeUpdateBtn.addEventListener('click', () => updateModal.style.display = 'none');
        window.addEventListener('click', e => { if (e.target === updateModal) updateModal.style.display = 'none'; });

        const detailsModal = document.getElementById('detailsModal');
        const closeDetailsBtn = document.getElementById('closeDetailsModalBtn');
        const toggleEditBtn = document.getElementById('toggleEditBtn');
        const saveDetailsBtn = document.getElementById('saveDetailsBtn');

        const teamInput = document.getElementById('details_modal_teammembers');
        const notesInput = document.getElementById('details_modal_notes');

        function lockModal() {
            teamInput.readOnly = true;
            notesInput.readOnly = true;
            teamInput.style.backgroundColor = 'var(--sidebar-bg)';
            notesInput.style.borderColor = 'transparent';
            toggleEditBtn.innerHTML = '<i class="fa-solid fa-lock"></i> Odblokuj';
            saveDetailsBtn.style.display = 'none';
            closeDetailsBtn.textContent = 'Zamknij';
        }

        document.querySelector('#assignments-table').addEventListener('click', function (e) {
            const target = e.target.closest('.open-details-modal');
            if (!target) return;

            document.getElementById('details_modal_assignment_id').value = target.dataset.id;
            document.getElementById('details_modal_title').textContent = "Szczegóły: " + target.dataset.title;

            teamInput.value = target.dataset.teammembers || '';
            notesInput.value = target.dataset.notes || '';

            lockModal();
            detailsModal.style.display = 'block';
        });

        toggleEditBtn.addEventListener('click', function() {
            if (teamInput.readOnly) {
                teamInput.readOnly = false;
                notesInput.readOnly = false;
                teamInput.style.backgroundColor = '#fff';
                teamInput.style.borderColor = 'var(--border-color-light)';
                toggleEditBtn.innerHTML = '<i class="fa-solid fa-lock-open"></i> Zablokuj i zapisz';
                saveDetailsBtn.style.display = 'flex';
                closeDetailsBtn.textContent = 'Anuluj';
            } else {
                saveDetailsBtn.click();
            }
        });

        const detailsForm = document.querySelector('#detailsModal form');
        detailsForm.addEventListener('submit', function(e) {
            e.preventDefault();
            toggleEditBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Zapisywanie...';
            
            fetch(this.action, { method: 'POST', body: new FormData(this) })
                .then(response => {
                    if (response.ok) {
                        const assignmentId = document.getElementById('details_modal_assignment_id').value;
                        const targetSpan = document.querySelector(`.open-details-modal[data-id="${assignmentId}"]`);
                        if (targetSpan) {
                            targetSpan.dataset.teammembers = teamInput.value;
                            targetSpan.dataset.notes = notesInput.value;
                        }
                        lockModal();
                    } else {
                        alert('Wystąpił błąd podczas zapisu.');
                        toggleEditBtn.innerHTML = '<i class="fa-solid fa-lock-open"></i> Zablokuj i zapisz';
                    }
                })
                .catch(() => {
                    alert('Wystąpił błąd połączenia z serwerem.');
                    toggleEditBtn.innerHTML = '<i class="fa-solid fa-lock-open"></i> Zablokuj i zapisz';
                });
        });

        closeDetailsBtn.addEventListener('click', () => detailsModal.style.display = 'none');
        window.addEventListener('click', e => { if (e.target === detailsModal) detailsModal.style.display = 'none'; });

        const assignmentForm = document.querySelector('#add-assignment-form');
        if (!assignmentForm) return;

        let isSubmitting = false;

        assignmentForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (isSubmitting) return;
            isSubmitting = true;

            const submitButton = e.submitter || assignmentForm.querySelector('button[type="submit"]');
            const submitButtons = document.querySelectorAll('button[form="add-assignment-form"]');
            const originalButtonText = submitButton.innerHTML;

            submitButtons.forEach(btn => btn.disabled = true);
            submitButton.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';

            const formData = new FormData();
            document.querySelectorAll('[form="add-assignment-form"]').forEach(input => {
                if (input.name) {
                    if (input.type === 'checkbox' || input.type === 'radio') {
                        if (input.checked) formData.append(input.name, input.value);
                    } else {
                        formData.append(input.name, input.value);
                    }
                }
            });

            if (submitButton && submitButton.name) {
                formData.append(submitButton.name, submitButton.value);
            }

            fetch('/assignment/insert', { method: 'POST', body: formData })
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
                        alert(data.message || 'Błąd wstawiania do bazy.');
                        return;
                    }
                    window.location.reload();
                })
                .catch(error => alert(error.message || 'Wystąpił błąd połączenia'))
                .finally(() => {
                    isSubmitting = false;
                    submitButtons.forEach(btn => btn.disabled = false);
                    submitButton.innerHTML = originalButtonText;
                });
        });
    });
</script>
</div>