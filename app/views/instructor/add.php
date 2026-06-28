<main class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
        <div>
            <h1>Prowadzący</h1>
            <p>Zarządzaj listą prowadzących i ich informacjami.</p>
        </div>
    </div>
</div>

<div class="form-card">
    <div class="form-header">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>
        <h2>Dodaj nowego prowadzącego</h2>
    </div>

    <form method="post" id="instructor-add-form" class="custom-form">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <div class="form-body">
            <div class="form-grid">
                <div class="form-group">
                    <label>Tytuł</label>
                    <select name="academic_title" class="form-control">
                        <option value="" disabled selected>Wybierz tytuł...</option>
                        <option value="mgr">mgr</option>
                        <option value="dr">dr</option>
                        <option value="dr hab.">dr hab.</option>
                        <option value="prof. dr hab.">prof. dr hab.</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label>Imię</label>
                    <input type="text" name="first_name" class="form-control" placeholder="Wpisz imię..." required>
                </div>

                <div class="form-group">
                    <label>Nazwisko</label>
                    <input type="text" name="last_name" class="form-control" placeholder="Wpisz nazwisko..." required>
                </div>

                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" class="form-control" placeholder="Wpisz adres e-mail...">
                </div>

                <div class="form-group">
                    <label>Pokój</label>
                    <input type="text" name="room" class="form-control" placeholder="Wpisz numer pokoju...">
                    <span class="input-help">Np. A.112, B.205, C.301</span>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="/instructor" class="btn-secondary">Anuluj</a>
            
            <button type="submit" name="action" value="save_and_new" class="btn-secondary">
                Zapisz i utwórz nowy
            </button>
            
            <button type="submit" name="action" value="save" class="btn-primary">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                Zapisz prowadzącego
            </button>
        </div>
    </form>
</div>

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

            const originalButtonText = submitButton.innerHTML; // Zmieniono na innerHTML by zachować ikonę SVG

            submitButtons.forEach(function (button) {
                button.disabled = true;
                button.style.opacity = '0.7';
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
                        button.style.opacity = '1';
                    });

                    submitButton.innerHTML = originalButtonText; // Przywracanie oryginalnej zawartości (z ikoną)
                });
        });
    });
</script>
</div>