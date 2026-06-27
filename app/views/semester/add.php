Dodaj semestr
<form method="post" id="semester-add-form">
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
    >

    <input type="text" name="name" placeholder="Nazwa semestru" >
    od
    <input type="date" name="start_date" placeholder="" required>
    do
    <input type="date" name="end_date" placeholder="" required>
    <button type="submit" name="action" value="save">
        Zapisz
    </button>

    <button type="submit" name="action" value="save_and_new">
        Zapisz i utwórz nowy
    </button>
</form>
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

            const originalButtonText = submitButton.textContent;

            submitButtons.forEach(function (button) {
                button.disabled = true;
            });

            submitButton.textContent = 'Dodawanie...';

            const formData = new FormData(semesterForm);

            if (submitButton.name) {
                formData.append(submitButton.name, submitButton.value);
            }

            fetch('/semester/insert', {
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

                    if (data.action === 'save_and_new') {
                        alert(data.message);
                        semesterForm.reset();

                        const firstInput = semesterForm.querySelector('input, select, textarea');

                        if (firstInput) {
                            firstInput.focus();
                        }

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