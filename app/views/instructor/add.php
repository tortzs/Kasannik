Dodaj instruktora
<form method="post" id="instructor-add-form">
    <input
        type="hidden"
        name="csrf_token"
        value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>"
    >

    <input type="email" name="email" placeholder="Email" >
    <input type="text" name="academic_title" placeholder="Tytuł" >
    <input type="text" name="first_name" placeholder="Imie" required>
    <input type="text" name="last_name" placeholder="Nazwisko" required>
    <input type="text" name="room" placeholder="Pokój" >
    <button type="submit" name="action" value="save">
        Zapisz
    </button>

    <button type="submit" name="action" value="save_and_new">
        Zapisz i utwórz nowy
    </button>
</form>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const instructorForm = document.querySelector('#instructor-add-form');

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

            submitButton.textContent = 'Dodawanie...';

            const formData = new FormData(instructorForm);

            if (submitButton.name) {
                formData.append(submitButton.name, submitButton.value);
            }

            fetch('/instructor/insert', {
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
                        instructorForm.reset();

                        const firstInput = instructorForm.querySelector('input, select, textarea');

                        if (firstInput) {
                            firstInput.focus();
                        }

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