<?php
if (!isset($subjects) && isset($subject)) {
    $subjects = $subject;
}
if (!is_array($subjects) || (isset($subjects['ID']) && !is_array(reset($subjects)))) {
    $subjects = [$subjects]; 
}
?>
<div class="page-header">
    <div class="header-title">
        <div class="title-icon"><i class="fa-solid fa-book"></i></div>
        <div>
            <h1>Przedmioty</h1>
            <p>Zarządzaj listą swoich przedmiotów</p>
        </div>
    </div>
    <a href="/subject/add" class="btn-primary" style="text-decoration: none;">
        <i class="fa-solid fa-plus"></i> Dodaj Przedmiot
    </a>
</div>

<div class="table-container">
    <?php if (!empty($subjects) && isset($subjects[0])) { ?>
        <table class="data-table">
            <thead>
            <tr>
                <th>Nazwa Przedmiotu</th>
                <th>ECTS</th>
                <th>Max Punktów</th>
                <th>Ocena Końcowa</th>
                <th style="text-align: center;">Akcje</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($subjects as $item) { ?>
                <tr>
                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($item['Name'] ?? 'Brak nazwy'); ?></td>
                    <td><span class="badge badge-teal"><?php echo (int)($item['ECTS'] ?? 0); ?> ECTS</span></td>
                    <td class="fw-bold"><?php echo (int)($item['MaxPossiblePoints'] ?? 0); ?> pkt</td>
                    <td class="text-gray fw-bold">
                        <?php echo !empty($item['FinalGrade']) ? htmlspecialchars($item['FinalGrade']) : '-'; ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="/subject/view/<?php echo $item['ID'] ?? '' ?>" class="btn-icon" style="color: var(--primary); border: 1px solid #b6e3de;" title="Szczegóły">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                            <a href="/subject/edit/<?php echo $item['ID'] ?? '' ?>" class="btn-icon edit" title="Edytuj">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <form method="post" action="/subject/delete" style="margin: 0;" onsubmit="return confirm('Na pewno usunąć przedmiot?');">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="subjectId" value="<?= (int)($item['ID'] ?? 0) ?>">
                                <button type="submit" class="btn-icon delete" title="Usuń"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div style="padding: 40px; text-align: center; color: var(--text-gray);">
            Brak przedmiotów w bazie. Kliknij przycisk powyżej, aby dodać pierwszy!
        </div>
    <?php } ?>
</div>