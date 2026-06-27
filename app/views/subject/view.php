<?php
/** @var array $subject */
/** @var array $assignments */
/** @var array $assignmentTypes */

?>
<h1>Szczegóły przedmiotu: <?php echo htmlspecialchars($subject['Name'] ?? ''); ?></h1>
<div style="margin-bottom: 20px;">
    <p><strong>ECTS:</strong> <?php echo (int)($subject['ECTS'] ?? 0); ?></p>
    <p><strong>Max Punktów:</strong> <?php echo (int)($subject['MaxPossiblePoints'] ?? 0); ?></p>
    <p><strong>Notatki:</strong> <?php echo htmlspecialchars($subject['GeneralNotes'] ?? ''); ?></p>
</div>

<h3>Zadania i Zaliczenia</h3>

<form id="add-assignment-form" method="post" action="/assignment/insert"></form>
<table id="assignments-table">
    <thead>
    <tr>
        <th>Tytuł</th>
        <th>Typ</th>
        <th>Max Punkty</th>
        <th>Zdobyte Pkt</th>
        <th>Status</th>
        <th>Deadline</th>
        <th>Akcje</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if (!empty($assignments)) {
        foreach ($assignments as $assignment) {
            ?>
            <tr>
                <td><?php echo htmlspecialchars($assignment['Title']); ?></td>
                <td><?php echo htmlspecialchars($assignment['TypeName']); ?></td>
                <td><?php echo htmlspecialchars($assignment['MaxPoints']); ?></td>
                <td><?php echo $assignment['EarnedPoints'] !== null ? htmlspecialchars($assignment['EarnedPoints']) : '-'; ?></td>
                <td><?php echo $assignment['IsCompleted'] ? 'Zakończone' : 'Do zrobienia'; ?></td>
                <td><?php echo htmlspecialchars($assignment['Deadline']); ?></td>
                <td>
                    <button type="button" class="open-update-modal" style="display:inline-block;"
                            data-id="<?php echo (int)$assignment['ID']; ?>"
                            data-points="<?php echo htmlspecialchars($assignment['EarnedPoints'] ?? ''); ?>"
                            data-completed="<?php echo (int)$assignment['IsCompleted']; ?>">
                        Aktualizuj
                    </button>

                    <form method="post" action="/assignment/delete" style="display:inline-block;" onsubmit="return confirm('Na pewno usunąć zadanie?');">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <input type="hidden" name="assignmentId" value="<?= (int)$assignment['ID'] ?>">
                        <input type="hidden" name="subjectId" value="<?= (int)$subject['ID'] ?>">
                        <button type="submit">Usuń</button>
                    </form>
                </td>
            </tr>
            <?php
        }
    }
    ?>
    </tbody>
    <tfoot>
    <tr>
        <td>
            <input form="add-assignment-form" type="text" name="assignment_title" placeholder="Tytuł zadania" required>
        </td>
        <td>
            <select form="add-assignment-form" name="assignment_type" required>
                <?php if(!empty($assignmentTypes)) {
                    foreach ($assignmentTypes as $type) { ?>
                        <option value="<?php echo $type['TypeID'] ?>">
                            <?php echo htmlspecialchars($type['TypeName']) ?>
                        </option>
                    <?php } } ?>
            </select>
        </td>
        <td>
            <input form="add-assignment-form" type="number" step="0.5" name="assignment_points" placeholder="Max pkt" required>
        </td>
        <td>
            <input form="add-assignment-form" type="datetime-local" name="assignment_deadline" required>
        </td>
        <td>
            <input form="add-assignment-form" type="hidden" name="assignment_subject" value="<?= (int)$subject['ID'] ?>">
            <input form="add-assignment-form" type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <button form="add-assignment-form" type="submit">Dodaj</button>
        </td>
    </tr>
    </tfoot>
</table>

<div style="margin-top: 20px;">
    <a href="/semester/view/<?php echo (int)($subject['SemesterID'] ?? 0); ?>">
        Wróć do semestru
    </a>
</div>

<div id="updateModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:25px; border-radius:8px; min-width:300px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <h3 style="margin-top:0;">Aktualizuj zadanie</h3>

        <form method="post" action="/assignment/update">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
            <input type="hidden" name="assignmentId" id="modal_assignment_id" value="">
            <input type="hidden" name="subjectId" value="<?= (int)$subject['ID'] ?>">

            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px;">Zdobyte punkty (opcjonalnie):</label>
                <input type="number" step="0.5" name="earned_points" id="modal_earned_points" value="" style="width:100%; padding:5px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label>
                    <input type="checkbox" name="is_completed" id="modal_is_completed" value="1">
                    Oznacz jako ukończone
                </label>
            </div>

            <div style="text-align:right;">
                <button type="button" id="closeModalBtn" style="margin-right:10px;">Anuluj</button>
                <button type="submit">Zapisz zmiany</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('updateModal');
        const closeBtn = document.getElementById('closeModalBtn');

        document.querySelectorAll('.open-update-modal').forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('modal_assignment_id').value = this.dataset.id;
                document.getElementById('modal_earned_points').value = this.dataset.points;
                document.getElementById('modal_is_completed').checked = (this.dataset.completed === '1');

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
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const assignmentForm = document.querySelector('#add-assignment-form');

        if (!assignmentForm) return;

        let isSubmitting = false;

        assignmentForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;

            const submitButton = e.submitter;
            const submitButtons = assignmentForm.querySelectorAll('button[type="submit"]');

            if (!submitButton) {
                isSubmitting = false;
                return;
            }

            const originalButtonText = submitButton.textContent;

            submitButtons.forEach(function (button) {
                button.disabled = true;
            });

            submitButton.textContent = 'Dodawanie...';

            const formData = new FormData(assignmentForm);

            if (submitButton.name) {
                formData.append(submitButton.name, submitButton.value);
            }
            ler
            fetch('/assignment/insert', {
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

                    const titleInput = document.querySelector('[form="add-assignment-form"][name="assignment_title"]');
                    const pointsInput = document.querySelector('[form="add-assignment-form"][name="assignment_points"]');
                    const deadlineInput = document.querySelector('[form="add-assignment-form"][name="assignment_deadline"]');
                    const typeSelect = document.querySelector('[form="add-assignment-form"][name="assignment_type"]');

                    const title = titleInput.value.trim();
                    const points = pointsInput.value.trim();
                    const deadline = deadlineInput.value.trim();
                    const typeName = typeSelect.options[typeSelect.selectedIndex].textContent.trim();

                    const tableBody = document.querySelector('#assignments-table tbody');

                    const row = document.createElement('tr');

                    row.innerHTML = `
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                  <form method="post" action="/assignment/delete" onsubmit="return confirm('Na pewno usunąć zadanie?');">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                    <input type="hidden" name="assignmentId" value="${data.assignmentId}">
                                    <input type="hidden" name="subjectId" value="<?= (int)$subject['ID'] ?>">
                                    <button type="submit">Usuń</button>
                                </form>
                            </td>
                    `;

                    row.children[0].textContent = title;
                    row.children[1].textContent = typeName;
                    row.children[2].textContent = points;
                    row.children[3].textContent = deadline;

                    tableBody.appendChild(row);
                    assignmentForm.reset();
                })
                .catch(function () {
                    alert('Wystąpił błąd połączenia');
                })
                .finally(function () {
                    isSubmitting = false;

                    submitButtons.forEach(function (button) {
                        button.disabled = false;
                    });

                    submitButton.textContent = originalButtonText;
                });
        });
    });
</script>