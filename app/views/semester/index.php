<?php
/** @var Int $userId */
/** @var array $semesters */
?>
<div class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon"><i class="fa-regular fa-calendar"></i></div>
        <div>
            <h1>Semestry</h1>
            <p>Zarządzaj swoimi semestrami</p>
        </div>
    </div>
    <a href="/semester/add" class="btn-primary" style="text-decoration: none;">
        <i class="fa-solid fa-plus"></i> Dodaj Semestr
    </a>
</div>

<div class="table-container">
    <?php if (!empty($semesters)) { ?>
        <table class="data-table">
            <thead>
            <tr>
                <th>Nazwa</th>
                <th>Rozpoczęcie</th>
                <th>Koniec</th>
                <th>Akcje</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($semesters as $semester) { ?>
                <tr>
                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($semester['Name']); ?></td>
                    <td><?php echo htmlspecialchars($semester['StartDate']); ?></td>
                    <td><?php echo htmlspecialchars($semester['EndDate']); ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="/semester/view/<?php echo $semester['ID']?>" class="btn-icon" style="color: var(--primary); border: 1px solid #b6e3de;"><i class="fa-solid fa-eye"></i></a>
                            <a href="/semester/edit/<?php echo $semester['ID']?>" class="btn-icon edit"><i class="fa-solid fa-pen"></i></a>
                            <form method="post" action="/semester/delete" style="margin: 0;" onsubmit="return confirm('Na pewno usunąć semestr?');">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="semesterId" value="<?= (int)$semester['ID'] ?>">
                                <button type="submit" class="btn-icon delete"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div style="padding: 40px; text-align: center; color: var(--text-gray);">
            Brak semestrów.
        </div>
    <?php } ?>
</div>
</div>