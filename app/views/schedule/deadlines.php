<?php
/** @var array $assignments */
?>
<div class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon"><i class="fa-solid fa-clipboard-list"></i></div>
        <div>
            <h1>Terminy</h1>
            <p>Lista wszystkich egzaminów, kolokwiów i zadań</p>
        </div>
    </div>
</div>

<div class="table-container">
    <?php if (!empty($assignments)) { ?>
        <table class="data-table">
            <thead>
            <tr>
                <th style="width: 25%;">Tytuł zadania</th>
                <th>Przedmiot</th>
                <th>Typ</th>
                <th>Data i godzina</th>
                <th>Status</th>
                <th style="text-align: center;">Akcje</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($assignments as $item) { 
                $isCompleted = (bool)$item['IsCompleted'];
                $deadlineTimestamp = strtotime($item['Deadline']);
                $formattedDate = $deadlineTimestamp ? date('d.m.Y', $deadlineTimestamp) . '<br><span style="font-size: 0.8rem; color: var(--text-gray);">' . date('H:i', $deadlineTimestamp) . '</span>' : '-';
                
                $daysLeft = '';
                if ($deadlineTimestamp && !$isCompleted) {
                    $diff = ceil(($deadlineTimestamp - time()) / 86400);
                    if ($diff < 0) {
                        $daysLeft = '<span class="badge badge-pink" style="margin-left: 10px;">Po terminie</span>';
                    } elseif ($diff == 0) {
                        $daysLeft = '<span class="badge badge-purple" style="margin-left: 10px;">Dzisiaj!</span>';
                    } elseif ($diff == 1) {
                        $daysLeft = '<span class="badge badge-purple" style="margin-left: 10px;">Jutro</span>';
                    } else {
                        $daysLeft = '<span class="badge badge-teal" style="margin-left: 10px;">Za ' . $diff . ' dni</span>';
                    }
                }
            ?>
                <tr>
                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($item['Title'] ?? ''); ?></td>
                    <td class="text-gray fw-bold"><?php echo htmlspecialchars($item['SubjectName'] ?? ''); ?></td>
                    <td><span class="badge badge-purple"><?php echo htmlspecialchars($item['TypeName'] ?? ''); ?></span></td>
                    <td class="fw-bold">
                        <div style="display: flex; align-items: center;">
                            <div><?php echo $formattedDate; ?></div>
                            <?php echo $daysLeft; ?>
                        </div>
                    </td>
                    <td>
                        <span class="badge <?php echo $isCompleted ? 'badge-teal' : 'badge-pink'; ?>">
                            <?php echo $isCompleted ? '<i class="fa-solid fa-check"></i> Zakończone' : 'Do zrobienia'; ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="/subject/view/<?php echo (int)($item['SubjectID'] ?? 0); ?>" class="btn-icon" style="color: var(--primary); border: 1px solid #b6e3de;" title="Przejdź do przedmiotu">
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <div style="padding: 40px; text-align: center; color: var(--text-gray);">
            Brak nadchodzących terminów. Możesz odpocząć!
        </div>
    <?php } ?>
</div>
</div>