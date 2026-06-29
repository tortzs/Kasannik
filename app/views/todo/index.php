<?php
/** @var array $tasks */
// Powyższa linijka podpowiada IDE, że zmienna $tasks to tablica przekazana z Kontrolera
?>

<div class="main-content">

    <div class="page-header" style="margin-bottom: 30px;">
        <h1>Lista To-Do</h1>
        <p>Zadania do zrobienia.</p>
    </div>

    <div class="todo-add-section" style="background: #f5f5f5; padding: 15px; border-radius: 6px; margin-bottom: 25px;">
        <h3>Dodaj nowe zadanie</h3>
        <form method="post" action="/todo/add">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div style="margin-bottom: 10px;">
                <label style="display:block; margin-bottom:5px;">Treść zadania:</label>
                <input type="text" name="task_desc" placeholder="Np. Skończyć projekt z bazy danych" style="width: 100%; padding: 8px;" required>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; margin-bottom:5px;">Data realizacji (opcjonalnie):</label>
                <input type="date" name="target_date" style="padding: 8px;">
            </div>

            <button type="submit" style="padding: 8px 15px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">
                Dodaj do listy
            </button>
        </form>
    </div>

    <div class="todo-list-section">
        <h3>Twoje zadania</h3>

        <?php if (empty($tasks)): ?>
            <p style="color: #666; font-style: italic;">Brak zadań na najbliższe dni.</p>
        <?php else: ?>
            <ul style="list-style: none; padding: 0;">
                <?php foreach ($tasks as $task): ?>
                    <li style="background: white; border: 1px solid #ddd; padding: 15px; margin-bottom: 10px; border-radius: 4px; display: flex; justify-content: space-between; align-items: center;">

                        <div style="display: flex; align-items: center; gap: 15px;">
                            <form method="post" action="/todo/toggle" style="margin: 0;">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="task_id" value="<?= (int)$task['ID']; ?>">
                                <input type="hidden" name="current_status" value="<?= (int)$task['IsCompleted']; ?>">

                                <input type="checkbox"
                                       onChange="this.form.submit()"
                                    <?= $task['IsCompleted'] == 1 ? 'checked' : ''; ?>
                                       style="transform: scale(1.3); cursor: pointer;">
                            </form>

                            <div>
                                <span style="<?= $task['IsCompleted'] == 1 ? 'text-decoration: line-through; color: #888;' : 'font-weight: bold;'; ?>">
                                    <?= htmlspecialchars($task['TaskDesc']); ?>
                                </span>

                                <?php if (!empty($task['TargetDate'])): ?>
                                    <div style="font-size: 0.85rem; color: #666; margin-top: 3px;">
                                        <?= htmlspecialchars(date('d.m.Y', strtotime($task['TargetDate']))); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div>
                            <form method="post" action="/todo/delete" style="margin: 0;" onSubmit="return confirm('Na pewno chcesz usunąć to zadanie?')">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="task_id" value="<?= (int)$task['ID']; ?>">
                                <button type="submit" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.85rem;">
                                    Usuń
                                </button>
                            </form>
                        </div>

                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

</div>