<?php
/** @var array $semester */
/** @var array $subjects */
/** @var array $instructors */
?>
<div class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon"><i class="fa-solid fa-book-open"></i></div>
        <div>
            <h1><?php echo htmlspecialchars($semester['Name'] ?? 'Szczegóły semestru'); ?></h1>
            <p><?php echo htmlspecialchars($semester['StartDate'] ?? ''); ?> - <?php echo htmlspecialchars($semester['EndDate'] ?? ''); ?></p>
        </div>
    </div>
</div>

<div class="form-card" style="margin-bottom: 30px;">
    <div class="form-header">
        <h2><i class="fa-solid fa-plus text-primary"></i> Szybkie dodawanie przedmiotu</h2>
    </div>
    <div class="form-body" style="padding-bottom: 20px;">
        <form id="add-subject-form" method="post" action="/subjects/add" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            <input type="hidden" name="subject_semester" value="<?= $semester['ID'] ?? '' ?>">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div style="flex: 2; min-width: 200px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; display: block;">Nazwa przedmiotu</label>
                <input class="form-control" type="text" name="subject_name" placeholder="np. Bazy Danych" required>
            </div>

            <div style="flex: 1; min-width: 100px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; display: block;">ECTS</label>
                <input class="form-control" type="number" name="subject_ects" placeholder="ECTS" min="0" required>
            </div>

            <div style="flex: 2; min-width: 200px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; display: block;">Prowadzący</label>
                <select class="form-control" name="subject_instructor" required>
                    <option value="" disabled selected>Wybierz prowadzącego...</option>
                    <?php if(!empty($instructors)) { foreach ($instructors as $instructor) { ?>
                        <option value="<?php echo $instructor['ID'] ?>">
                            <?php echo htmlspecialchars($instructor['FirstName'] . ' ' . $instructor['LastName']) ?>
                        </option>
                    <?php } } ?>
                </select>
            </div>

            <button type="submit" class="btn-primary" style="height: 44px;">
                Dodaj
            </button>
        </form>
    </div>
</div>

<div class="table-container">
    <div class="table-toolbar">
        <h2>Przedmioty w tym semestrze</h2>
    </div>
    <table class="data-table" id="subjects-table">
        <thead>
        <tr>
            <th>Nazwa</th>
            <th>ECTS</th>
            <th>Prowadzący</th>
            <th>Linki</th>
            <th>Punkty</th>
            <th>Opis</th>
            <th>Akcje</th>
        </tr>
        </thead>
        <tbody>
        <?php if(!empty($subjects)) { foreach ($subjects as $subject) {
            $linksArray = [];
            if (!empty($subject['LinksData'])) {
                $pairs = explode('||', $subject['LinksData']);
                foreach ($pairs as $pair) {
                    $parts = explode('::', $pair);
                    if (count($parts) === 2) {
                        $linksArray[$parts[0]] = $parts[1];
                    }
                }
            }
            $usosUrl = $linksArray['USOS'] ?? '';
            $moodleUrl = $linksArray['KURS'] ?? '';
            ?>
            <tr>
                <td class="fw-bold text-dark"><?php echo htmlspecialchars($subject['SubjectName'] ?? ''); ?></td>
                <td><span class="badge badge-teal"><?php echo htmlspecialchars($subject['SubjectECTS'] ?? ''); ?> ECTS</span></td>
                <td class="text-gray"><?php echo htmlspecialchars(($subject['LecturerFirstName'] ?? '') . ' ' . ($subject['LecturerLastName'] ?? '')); ?></td>
                <td>
                    <div style="display: flex; gap: 5px;">
                        <?php if (!empty($usosUrl)): ?>
                            <a href="<?php echo htmlspecialchars($usosUrl); ?>" target="_blank" style="text-decoration: none;">
                                <span class="badge" style="background-color: #e4eeeb; color: var(--color-blue);">USOS</span>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($moodleUrl)): ?>
                            <a href="<?php echo htmlspecialchars($moodleUrl); ?>" target="_blank" style="text-decoration: none;">
                                <span class="badge" style="background-color: #fbe4eb; color: var(--color-orange);">KURS</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="fw-bold"><?php echo htmlspecialchars($subject['SubjectPoints'] ?? '0') . ' / ' . htmlspecialchars($subject['SubjectMaxPossiblePoints'] ?? '0'); ?></td>
                <td class="text-gray" style="font-size: 0.85rem; max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <?php echo htmlspecialchars($subject['SubjectDescription'] ?? ''); ?>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="/subject/view/<?php echo urlencode($subject['SubjectID'] ?? '') ?>" class="btn-icon" style="color: var(--primary); border: 1px solid #b6e3de;"><i class="fa-solid fa-arrow-right"></i></a>
                        <button type="button" class="btn-icon edit open-update-modal" 
                                data-id="<?php echo (int)($subject['SubjectID'] ?? 0); ?>"
                                data-maxpoints="<?php echo htmlspecialchars($subject['SubjectMaxPossiblePoints'] ?? ''); ?>"
                                data-description="<?php echo htmlspecialchars($subject['SubjectDescription'] ?? ''); ?>"
                                data-usoslink="<?php echo htmlspecialchars($usosUrl); ?>"
                                data-moodlelink="<?php echo htmlspecialchars($moodleUrl); ?>">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form method="post" action="/subject/delete" style="margin: 0;" onsubmit="return confirm('Na pewno usunąć przedmiot?');">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <input type="hidden" name="subjectId" value="<?= (int)($subject['SubjectID'] ?? 0) ?>">
                            <input type="hidden" name="semesterId" value="<?= (int)($subject['SemesterID'] ?? 0) ?>">
                            <button type="submit" class="btn-icon delete"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php } } ?>
        </tbody>
    </table>
</div>

<div id="updateSubjectModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width: 100%; max-width: 500px; padding: 20px;">
        <div class="form-card" style="margin: 0;">
            <div class="form-header">
                <h2>Aktualizuj przedmiot</h2>
            </div>
            <div class="form-body">
                <form method="post" action="/subject/update">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <input type="hidden" name="subjectId" id="modal_subject_id" value="">
                    <input type="hidden" name="semesterId" value="<?= (int)($semester['ID'] ?? 0) ?>">

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Całkowita liczba punktów:</label>
                        <input class="form-control" type="number" min="0" max="1000" step="0.5" name="max_points" id="modal_max_points" value="">
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Opis / Notatki:</label>
                        <textarea class="form-control" name="description" id="modal_description" style="min-height:80px; resize: vertical;"></textarea>
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Link USOSweb:</label>
                        <input class="form-control" type="url" name="usos_link" id="modal_usos_link" placeholder="https://usosweb.polsl.pl/...">
                    </div>

                    <div class="form-group" style="margin-bottom: 20px;">
                        <label>Link KURS (Moodle):</label>
                        <input class="form-control" type="url" name="moodle_link" id="modal_moodle_link" placeholder="https://platforma.polsl.pl/...">
                    </div>

                    <div class="form-actions" style="padding: 10px 0 0 0;">
                        <button type="button" id="closeSubjectModalBtn" class="btn-secondary">Anuluj</button>
                        <button type="submit" class="btn-primary">Zapisz zmiany</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('updateSubjectModal');
        const closeBtn = document.getElementById('closeSubjectModalBtn');

        document.querySelectorAll('.open-update-modal').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('modal_subject_id').value = this.dataset.id;
                document.getElementById('modal_max_points').value = this.dataset.maxpoints;
                document.getElementById('modal_description').value = this.dataset.description;
                document.getElementById('modal_usos_link').value = this.dataset.usoslink || '';
                document.getElementById('modal_moodle_link').value = this.dataset.moodlelink || '';
                modal.style.display = 'block';
            });
        });

        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        const subjectForm = document.querySelector('#add-subject-form');
        if (!subjectForm) return;
        let isSubmitting = false;

        subjectForm.addEventListener('submit', function (e) {
            e.preventDefault();
            if (isSubmitting) return;
            isSubmitting = true;

            const submitButton = e.submitter || subjectForm.querySelector('button[type="submit"]');
            const submitButtons = subjectForm.querySelectorAll('button[type="submit"]');

            if (!submitButton) {
                isSubmitting = false;
                return;
            }

            const originalButtonText = submitButton.textContent;
            submitButtons.forEach(btn => btn.disabled = true);
            submitButton.textContent = 'Dodawanie...';

            const formData = new FormData(subjectForm);
            if (submitButton.name) formData.append(submitButton.name, submitButton.value);

            fetch('/subject/insert', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    alert(data.message);
                    return;
                }
                window.location.reload(); 
            })
            .catch(() => alert('Wystąpił błąd połączenia'))
            .finally(() => {
                isSubmitting = false;
                submitButtons.forEach(btn => btn.disabled = false);
                submitButton.textContent = originalButtonText;
            });
        });
    });
</script>
</div>