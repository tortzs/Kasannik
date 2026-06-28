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
            Szczegóły
        </th>
        <th>
            Akcje
        </th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach ($subjects as $subject) {
        var_dump($subjects) //do usuniecia
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
                <?php echo $subject['SubjectPoints'] . '/' . $subject['SubjectMaxPossiblePoints']; ?>
            </td>
            <td>
                <?php echo $subject['SubjectDescription']; ?>
            </td>
            <td>
                <a href="/subject/view/<?php echo urlencode($subject['SubjectID']) ?>">
                    <button type="button">Przejdź</button>
                </a>
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

