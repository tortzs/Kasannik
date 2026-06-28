<?php
/** @var array $semester */
/** @var array $subjects */
/** @var array $instructors */

?>
Aktualny semestr
<?php
// Tutaj macie zdumpowany array z rzeczami, zrobcie ladnosc
var_dump($semester);

?>
<form id="add-subject-form" method="post" action="/subjects/add"></form>
<table id="subjects-table">
    <thead>
    <tr>
        <th>
            Nazwa
        </th>
        <th>
            ECTS
        </th>
        <th>
            Instruktor
        </th>
        <th>
            Punkty
        </th>
        <th>
            Opis
        </th>
        <th>
            Akcje
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($subjects as $subject) {
        ?>
        <tr>
            <td>
                <?php echo $subject['SubjectName']; ?>
            </td>
            <td>
                <?php echo $subject['SubjectECTS']; ?>

            </td>
            <td>
                <?php echo $subject['LecturerFirstName'] . ' ' . $subject['LecturerLastName']; ?>
            </td>
            <td>
                <?php echo $subject['SubjectPoints'] . ' / ' . $subject['SubjectMaxPossiblePoints']; ?>
            </td>
            <td>
                <?php echo $subject['SubjectDescription']; ?>
            </td>
            <td>
                <a href="/subject/view/<?php echo urlencode($subject['SubjectID']) ?>">
                    <button type="button">Przejdź</button>
                </a>
                <button type="button" class="open-update-modal" style="display:inline-block;"
                        data-id="<?php echo (int)$subject['SubjectID']; ?>"
                        data-maxpoints="<?php echo htmlspecialchars($subject['SubjectMaxPossiblePoints'] ?? ''); ?>"
                        data-description="<?php echo htmlspecialchars($subject['SubjectDescription'] ?? ''); ?>">
                    Aktualizuj
                </button>
                <form method="post" action="/subject/delete" style="display:inline-block;" onsubmit="return confirm('Na pewno usunąć przedmiot?');">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="subjectId" value="<?= (int)$subject['SubjectID'] ?>">
                    <input type="hidden" name="semesterId" value="<?= (int)$subject['SemesterID'] ?>">
                    <button type="submit">
                        Usuń
                    </button>
                </form>
            </td>
        </tr>
        <?php
    }
    ?>

    </tbody>
    <tfoot>
    <tr>
        <td>
            <input
                    form="add-subject-form"
                    type="text"
                    name="subject_name"
                    placeholder="Nazwa przedmiotu"
                    required
            >
        </td>

        <td>
            <input
                    form="add-subject-form"
                    type="number"
                    name="subject_ects"
                    placeholder="ECTS"
                    min="0"
                    required
            >
        </td>
        <td>
            <select
                    form="add-subject-form"
                    name="subject_instructor"
                    required
            >
                <?php foreach ($instructors as $instructor) { ?>
                    <option value="<?php echo $instructor['ID'] ?>">
                        <?php echo $instructor['FirstName'] . ' ' . $instructor['LastName'] ?>
                    </option>
                <?php } ?>

            </select>
        </td>

        <td>
            <input
                    form="add-subject-form"
                    type="hidden"
                    name="subject_semester"
                    value="<?= $semester['ID'] ?>"
            > <input
                    form="add-subject-form"
                    type="hidden"
                    name="csrf_token"
                    value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
            >

            <button form="add-subject-form" type="submit">
                Dodaj
            </button>
        </td>
    </tr>
    </tfoot>

</table>

<div id="updateSubjectModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); background:white; padding:25px; border-radius:8px; min-width:300px; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
        <h3 style="margin-top:0;">Aktualizuj przedmiot</h3>

        <form method="post" action="/subject/update">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

            <input type="hidden" name="subjectId" id="modal_subject_id" value="">

            <input type="hidden" name="semesterId" value="<?= (int)$semester['ID'] ?>">

            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px;">Całkowita liczba punktów:</label>
                <input type="number" step="0.5" name="max_points" id="modal_max_points" value="" style="width:100%; padding:5px;">
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display:block; margin-bottom:5px;">Opis / Notatki:</label>
                <textarea name="description" id="modal_description" style="width:100%; padding:5px; min-height:80px;"></textarea>
            </div>

            <div style="text-align:right;">
                <button type="button" id="closeSubjectModalBtn" style="margin-right:10px;">Anuluj</button>
                <button type="submit">Zapisz zmiany</button>
            </div>
        </form>
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
        const subjectForm = document.querySelector('#add-subject-form');

        if (!subjectForm) return;

        let isSubmitting = false;

        subjectForm.addEventListener('submit', function (e) {
            e.preventDefault();

            if (isSubmitting) return;
            isSubmitting = true;

            const submitButton = e.submitter;
            const submitButtons = subjectForm.querySelectorAll('button[type="submit"]');

            if (!submitButton) {
                isSubmitting = false;
                return;
            }

            const originalButtonText = submitButton.textContent;

            submitButtons.forEach(function (button) {
                button.disabled = true;
            });

            submitButton.textContent = 'Dodawanie...';

            const formData = new FormData(subjectForm);

            if (submitButton.name) {
                formData.append(submitButton.name, submitButton.value);
            }

            fetch('/subject/insert', {
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

                    const subjectNameInput = document.querySelector('[form="add-subject-form"][name="subject_name"]');
                    const subjectEctsInput = document.querySelector('[form="add-subject-form"][name="subject_ects"]');
                    const subjectInstructorSelect = document.querySelector('[form="add-subject-form"][name="subject_instructor"]');

                    const subjectName = subjectNameInput.value.trim();
                    const subjectEcts = subjectEctsInput.value.trim();

                    const subjectInstructor = subjectInstructorSelect.options[
                        subjectInstructorSelect.selectedIndex
                        ].textContent.trim();

                    const subjectTable = document.querySelector('#subjects-table');
                    const tableBody = subjectTable.querySelector('tbody');

                    const row = document.createElement('tr');
                    console.log(data);
                    row.innerHTML = `
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                  <form method="post" action="/subject/delete" onsubmit="return confirm('Na pewno usunąć przedmiot?');">
                                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                                    <input type="hidden" name="subjectId" value="${data.subjectId}">
                                    <input type="hidden" name="semesterId" value="<?= (int)$semester['ID'] ?>">
                                    <button type="submit">
                                        Usuń
                                    </button>
                                </form>
                            </td>
                    `;

                    row.children[0].textContent = subjectName;
                    row.children[1].textContent = subjectEcts;
                    row.children[2].textContent = subjectInstructor;

                    tableBody.appendChild(row);
                    subjectForm.reset();
                    return;

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

