<?php
/** @var array $upcomingAssignments
 * @var array $upcomingTodos
 * @var array $todayClasses
 * @var array $tomorrowClasses
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
                        <?php
                        $typeClass = match($assignment['TypeName']) {
                            'Kolokwium' => 'KOLOS',
                            'Projekt Grupowy' => 'PROJ',
                            'Egzamin Pisemny' => 'EGZ',
                            'Zadanie Domowe' => 'ZAD',
                            'Sprawozdanie Laboratoryjne' => 'ZAD',
                            'Projekt Indywidualny' => 'PROJ',
                            default => 'DEF'
                        };
                        ?>

                        <div class="item-icon <?= $typeClass ?>"><i class="fa-regular fa-calendar"></i></div>
                        <div class="item-details">
                            <div class="item-title <?= $typeClass ?>-text"><?= htmlspecialchars($assignment['Title']) ?></div>
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
                            <div class="item-badge <?= $typeClass ?>-bg"><?= htmlspecialchars($daysText) ?></div>
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
                        <input type="checkbox" onclick="window.location.href='/todo'; return false;">
                        <span class="todo-text"><?= htmlspecialchars($todo['TaskDesc']) ?></span>
                        <span class="priority-badge medium"><?= htmlspecialchars($todo['TargetDate']) ?>
                    </label>
                    <?php 
                    endforeach;
                    ?>
                </div>
            </div>

            <div class="card schedule-card full-width">
                <div class="card-header">
                    <h2>
                        <i class="fa-regular fa-calendar-days"></i>
                        Plan zajęć
                    </h2>
                    <a href="/schedule" class="add-btn">
                        <i class="fa-solid fa-arrow-right"></i>
                    </a>
                </div>
                <div class="card-list">
                    <h3>Dzisiaj</h3>
                    <?php foreach($todayClasses as $lesson): ?>

                        <div class="list-item">
                            <div class="item-icon <?= $lesson['Classtype'] ?>">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="item-details">
                                <div class="item-title">
                                    <?= htmlspecialchars($lesson['SubjectName']) ?>
                                </div>
                                <div class="item-desc">
                                    <?= substr($lesson['StartTime'],0,5) ?>
                                    -
                                    <?= substr($lesson['EndTime'],0,5) ?>
                                    <br>
                                    Sala:
                                    <?= htmlspecialchars($lesson['Room']) ?>
                                </div>
                            </div>
                            <div class="item-meta">
                                <div class="item-badge <?= $lesson['Classtype'] ?>-bg">
                                    <?= htmlspecialchars($lesson['Classtype']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <h3 style="margin-top:20px;">Jutro</h3>
                    <?php foreach($tomorrowClasses as $lesson): ?>
                        <div class="list-item">
                            <div class="item-icon <?= $lesson['Classtype'] ?>">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="item-details">
                                <div class="item-title">
                                    <?= htmlspecialchars($lesson['SubjectName']) ?>
                                </div>
                                <div class="item-desc">
                                    <?= substr($lesson['StartTime'],0,5) ?>
                                    -
                                    <?= substr($lesson['EndTime'],0,5) ?>
                                    <br>
                                    Sala:
                                    <?= htmlspecialchars($lesson['Room']) ?>
                                </div>
                            </div>
                            <div class="item-meta">
                                <div class="item-badge <?= $lesson['Classtype'] ?>-bg">
                                    <?= htmlspecialchars($lesson['Classtype']) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>


                </div>

            </div>
        </div>
    </main>
</body>
</html>