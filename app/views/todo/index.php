<?php
/** @var array $tasks */
// Powyższa linijka podpowiada IDE, że zmienna $tasks to tablica przekazana z Kontrolera
?>
<div class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon"><i class="fa-solid fa-list-check"></i></div>
        <div>
            <h1>Lista To-Do</h1>
            <p>Zarządzaj swoimi codziennymi zadaniami</p>
        </div>
    </div>
</div>

<div class="form-card" style="margin-bottom: 30px;">
    <div class="form-header">
        <h2><i class="fa-solid fa-plus text-primary" style="margin-right: 8px;"></i> Dodaj nowe zadanie</h2>
    </div>
    <div class="form-body" style="padding-bottom: 20px;">
        <form method="post" action="/todo/add" style="display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

            <div style="flex: 2; min-width: 250px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; display: block;">Treść zadania</label>
                <input class="form-control" type="text" name="task_desc" placeholder="Np. Skończyć projekt z bazy danych" required>
            </div>

            <div style="flex: 1; min-width: 150px;">
                <label style="font-size: 0.85rem; font-weight: 700; color: var(--text-dark); margin-bottom: 8px; display: block;">Data realizacji (opcjonalnie)</label>
                <input class="form-control" type="date" name="target_date">
            </div>

            <button type="submit" class="btn-primary" style="height: 44px;">
                <i class="fa-solid fa-plus"></i> Dodaj
            </button>
        </form>
    </div>
</div>

<div class="card full-width">
    <div class="card-header" style="border-bottom: 1px solid var(--border-color-light); padding-bottom: 15px; margin-bottom: 0;">
        <h2><i class="fa-solid fa-tasks" style="margin-right: 8px;"></i> Twoje zadania</h2>
    </div>
    
    <div class="card-list" style="padding: 15px 0 5px;">
        <?php if (empty($tasks)): ?>
            <p style="color: var(--text-gray); font-style: italic; text-align: center; padding: 20px 0;">Brak zadań na najbliższe dni.</p>
        <?php else: ?>
            <?php foreach ($tasks as $task): 
                $isCompleted = $task['IsCompleted'] == 1;
            ?>
                <div class="list-item" style="display: flex; align-items: center; justify-content: space-between; padding: 15px 5px; border-bottom: 1px solid var(--border-color-light);">
                    
                    <div class="todo-item <?= $isCompleted ? 'done' : '' ?>" style="flex: 1; margin: 0; padding: 0;">
                        <form method="post" action="/todo/toggle" style="margin: 0; display: flex; align-items: center; width: 100%; gap: 15px;">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <input type="hidden" name="task_id" value="<?= (int)$task['ID']; ?>">
                            <input type="hidden" name="current_status" value="<?= (int)$task['IsCompleted']; ?>">
                            
                            <input type="checkbox" onChange="this.form.submit()" <?= $isCompleted ? 'checked' : ''; ?>>
                            
                            <span class="todo-text"><?= htmlspecialchars($task['TaskDesc']); ?></span>
                            
                            <?php if (!empty($task['TargetDate'])): 
                                $targetDate = strtotime($task['TargetDate']);
                                $diff = ceil(($targetDate - time()) / 86400);
                                
                                $badgeClass = 'badge-teal';
                                if ($isCompleted) {
                                    $badgeClass = 'badge-purple'; // Neutralne, jeśli już zrobione
                                } else if ($diff < 0) {
                                    $badgeClass = 'badge-pink'; // Przeterminowane
                                } else if ($diff <= 1) {
                                    $badgeClass = 'badge-pink'; // Pilne (dziś/jutro)
                                }
                            ?>
                                <span class="badge <?= $badgeClass ?>" style="margin-left: auto; white-space: nowrap;">
                                    <i class="fa-regular fa-calendar"></i> <?= htmlspecialchars(date('d.m.Y', $targetDate)); ?>
                                </span>
                            <?php endif; ?>
                        </form>
                    </div>

                    <div class="action-buttons" style="margin-left: 20px;">
                        <form method="post" action="/todo/delete" style="margin: 0;" onSubmit="return confirm('Na pewno chcesz usunąć to zadanie?')">
                            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                            <input type="hidden" name="task_id" value="<?= (int)$task['ID']; ?>">
                            <button type="submit" class="btn-icon delete" title="Usuń zadanie">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>

                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
</div>