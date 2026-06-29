<?php
/** @var array $upcomingAssignments
 * @var array $upcomingTodos
 * @var array $classesTomorrow
 */
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <main class="main-content">
        <div class="dashboard-grid">
            <div class="card exams-card">
                <div class="card-header">
                    <h2><i class="fa-regular fa-calendar-check"></i> Najbliższe egzaminy / rzeczy do oddania</h2>
                </div>
                <div class="card-list">
                    <?php
                    foreach ($upcomingAssignments as $assignment):
                    ?>
                    <div class="list-item">
                        <div class="item-icon pink"><i class="fa-regular fa-calendar"></i></div>
                        <div class="item-details">
                            <div class="item-title pink-text"><?= htmlspecialchars($assignment['Title']) ?></div>
                            <div class="item-desc">
                                <?= htmlspecialchars($assignment['TypeName'] ?? '') ?>
                                <?= !empty($assignment['Notes']) ? htmlspecialchars(" - ".$assignment['Notes']) : '' ?>
                            </div>
                        </div>
                        <?php
                        $deadlineTimestamp = strtotime($assignment['Deadline']);

                        $daysText = '';

                        if ($deadlineTimestamp && !$assignment['IsCompleted']) {

                            $diff = ceil(($deadlineTimestamp - time()) / 86400);

                            if ($diff < 0) {
                                $daysText = "Po terminie";
                            } elseif ($diff == 0) {
                                $daysText = "Dzisiaj!";
                            } elseif ($diff == 1) {
                                $daysText = "Jutro";
                            } else {
                                $daysText = "Za ".$diff." dni";
                            }
                        }
                        ?>
                        <div class="item-meta">
                            <div class="item-date"><?= date('d.m.Y', strtotime($assignment['Deadline'])) ?></div>
                            <div class="item-badge pink-bg"><?= htmlspecialchars($daysText) ?></div>
                        </div>
                    </div>
                    <?php
                    endforeach;
                    ?>
                </div>
            </div>

            <div class="card todo-card">
                <div class="card-header">
                    <h2><i class="fa-regular fa-square-check"></i> Lista do zrobienia</h2>
                    <a href="#" class="add-btn"><i class="fa-solid fa-plus"></i></a>
                </div>
                <div class="card-list">
                    <?php
                    foreach ($upcomingTodos as $todo):
                    ?>
                    <label class="todo-item">
                        <input type="checkbox">
                        <span class="todo-text"><?= htmlspecialchars($todo['TaskDesc']) ?></span>
                        <span class="priority-badge high"><?= htmlspecialchars($todo['TargetDate']) ?>
                    </label>
                    <?php 
                    endforeach;
                    ?>
                </div>
            </div>

            <div class="card schedule-card full-width">
                <div class="card-header">
                    <h2><i class="fa-regular fa-calendar-days"></i> Plan zajęć</h2>
                    <div class="schedule-nav">
                        <button><i class="fa-solid fa-chevron-left"></i></button>
                        <span>19 – 24 maja 2024</span>
                        <button><i class="fa-solid fa-chevron-right"></i></button>
                        <button class="btn-today">Dzisiaj</button>
                    </div>
                </div>
                <div class="schedule-table-wrapper">
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Pon.<br><span>19.05</span></th>
                                <th>Wt.<br><span>20.05</span></th>
                                <th>Śr.<br><span>21.05</span></th>
                                <th>Czw.<br><span>22.05</span></th>
                                <th>Pt.<br><span>23.05</span></th>
                                <th>Sob.<br><span>24.05</span></th>
                                <th>Nd.<br><span>25.05</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            // foreach ($schedule as $row): 
                            ?>
                            <tr>
                                <td class="time-col"><span>8:30 – 10:00</span></td>
                                <td class="subject-pink"></i>Matematyka Dyskretna</td>
                                <td class="subject-teal"></i>ZSI</td>
                                <td class=""></i></td>
                                <td class="subject-purple"></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                            </tr>
                            <tr>
                                <td class="time-col"><span>10:15 – 11:45</span></td>
                                <td class=""></i>Matematyka Dyskretna</td>
                                <td class=""></i>Matematyka Dyskretna</td>
                                <td class=""></i>Matematyka Dyskretna</td>
                                <td class=""></i>Matematyka Dyskretna</td>
                                <td class=""></i>Matematyka Dyskretna</td>
                                <td class=""></i>Matematyka </td>
                                <td class=""></i>Matematyka </td>
                            </tr>
                            <tr>
                                <td class="time-col"><span>12:00 – 13:30</span></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>

                            </tr>
                            <tr>
                                <td class="time-col"><span>13:45 – 15:15</span></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                            </tr>
                            <tr>
                                <td class="time-col"><span>15:30 – 17:00</span></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                                <td class=""></i></td>
                            </tr>
                            <?php 
                            // endforeach; 
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>
</html>