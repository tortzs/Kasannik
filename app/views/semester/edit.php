<?php
/* @var array $semester */
?>

Edytuj semestr <strong><?php echo $semester['Name'] ?></strong>
<form method="post" id="semester-edit-form">
    <input
            type="hidden"
            name="csrf_token"
            value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
    >
   <input
            type="hidden"
            name="semesterId"
            value="<?= htmlspecialchars($semester['ID']) ?>"
    >

    <input type="text" value="<?php echo $semester['Name'] ?>" name="name" placeholder="Nazwa semestru" >
    od
    <input type="date" value="<?php echo $semester['StartDate'] ?>" name="start_date" placeholder="" required>
    do
    <input type="date" value="<?php echo $semester['EndDate'] ?>"   name="end_date" placeholder="" required>
    <button type="submit" name="action" value="save">
        Edytuj
    </button>

</form>
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

            const originalButtonText = submitButton.textContent;

            submitButtons.forEach(function (button) {
                button.disabled = true;
            });

            submitButton.textContent = 'Edytowanie...';

            const formData = new FormData(semesterForm);

            if (submitButton.name) {
                formData.append(submitButton.name, submitButton.value);
            }

            fetch('/semester/update', {
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

                    window.location.href = '/semester';
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