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
                <td><?php echo htmlspecialchars($assignment['Deadline']); ?></td>
                <td>
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

            // Uderzamy do kontrolera AssignmentController
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