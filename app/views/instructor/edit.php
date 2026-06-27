<?php
/* @var array $instructor */

?>

Edytuj instruktora
<form method="post" id="instructor-edit-form">
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
    >
    <input type="hidden" name="instructorId" value="<?= (int)$instructor['ID'] ?>">
    <input type="email"  value="<?php echo $instructor['Email'] ?>"  name="email" placeholder="Email" >
    <input type="text"  value="<?php echo $instructor['AcademicTitle'] ?>"  name="academic_title" placeholder="Tytuł" >
    <input type="text"  value="<?php echo $instructor['FirstName'] ?>"  name="first_name" placeholder="Imie" required>
    <input type="text"  value="<?php echo $instructor['LastName'] ?>"  name="last_name" placeholder="Nazwisko" required>
    <input type="text"  value="<?php echo $instructor['Room'] ?>"  name="room" placeholder="Pokój" >
    <button type="submit" name="action" value="save">
        Zapisz
    </button>
</form>
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

            submitButtons.forEach(function (button) {
                button.disabled = true;
            });

            submitButton.textContent = 'Edytowanie...';

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

                    window.location.href = '/instructor';
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