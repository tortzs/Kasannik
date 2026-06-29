<?php
/** @var array $events */
/** @var array|null $semester */

// Definiujemy ramy czasowe siatki (od 7:00 do 18:00)
$startHour = 7;
$endHour = 18;

// Dni robocze
$daysOfWeek = [
        1 => 'Poniedziałek', 2 => 'Wtorek', 3 => 'Środa', 4 => 'Czwartek', 5 => 'Piątek'
];

$groupedEvents = [];
foreach ($events as $event) {
    $groupedEvents[$event['DayOfWeek']][] = $event;
}
?>

<div class="main-content" style="padding: 20px;">
    <div class="page-header" style="margin-bottom: 20px;">
        <h1>Plan Zajęć</h1>
        <?php if ($semester): ?>
            <p id="semester-boundary" data-start="<?= htmlspecialchars($semester['StartDate']) ?>" data-end="<?= htmlspecialchars($semester['EndDate']) ?>">
                Aktywny semestr: <strong><?= htmlspecialchars($semester['Name']) ?></strong>
                (Od <?= htmlspecialchars($semester['StartDate']) ?> do <?= htmlspecialchars($semester['EndDate']) ?>)
            </p>
        <?php else: ?>
            <p style="color: red;">Brak aktywnego semestru!</p>
        <?php endif; ?>
    </div>

    <div class="timetable-wrapper" style="display: flex; background: #fff; border: 1px solid #ccc; font-family: sans-serif; min-width: 800px;">

        <div class="time-labels" style="width: 60px; border-right: 1px solid #ccc; background: #eaeaea;">
            <div style="height: 40px; background: #fff; border-bottom: 1px solid #ccc;"></div> <?php for ($h = $startHour; $h < $endHour; $h++): ?>
                <div style="height: 60px; text-align: right; padding-right: 5px; font-size: 12px; color: #555; border-bottom: 1px solid #ccc; box-sizing: border-box;">
                    <?= $h ?>:00
                </div>
            <?php endfor; ?>
        </div>

        <?php foreach ($daysOfWeek as $dayNum => $dayName): ?>
            <div class="day-col" style="flex: 1; border-right: 1px solid #ccc; position: relative;">

                <div style="text-align: center; background: #4b4a68; color: white; padding: 10px 0; font-size: 14px; height: 40px; border-bottom: 1px solid #ccc; box-sizing: border-box;">
                    <?= $dayName ?>
                </div>

                <div class="day-grid" style="position: relative; height: <?= ($endHour - $startHour) * 60 ?>px; background-image: repeating-linear-gradient(to bottom, transparent, transparent 59px, #ddd 60px);">

                    <?php if (!empty($groupedEvents[$dayNum])): ?>
                        <?php foreach ($groupedEvents[$dayNum] as $event): ?>
                            <?php
                            // MATEMATYKA POZYCJI (1 minuta = 1 piksel)
                            $startParts = explode(':', $event['StartTime']);
                            // Odległość od góry (np. dla 8:30 -> (8-7)*60 + 30 = 90px)
                            $topPx = (($startParts[0] - $startHour) * 60) + $startParts[1];

                            $endParts = explode(':', $event['EndTime']);
                            // Wysokość bloku (czas trwania w minutach)
                            $duration = (($endParts[0] * 60) + $endParts[1]) - (($startParts[0] * 60) + $startParts[1]);

                            // Kolorowanie klocków (żółte dla wykładów, białe dla reszty, jak na Twoim screenie)
                            $bgColor = stripos($event['ClassType'], 'wyk') !== false ? '#fdf4c8' : '#ffffff';
                            ?>

                            <div style="position: absolute; top: <?= $topPx ?>px; height: <?= $duration ?>px; left: 2%; width: 96%; background: <?= $bgColor ?>; border: 1px solid #999; box-sizing: border-box; padding: 4px; font-size: 11px; overflow: hidden; box-shadow: 2px 2px 5px rgba(0,0,0,0.1); display: flex; flex-direction: column; justify-content: center; text-align: center;">
                                <div style="color: #666; font-size: 9px; position: absolute; top: 2px; left: 4px;">
                                    <?= htmlspecialchars(date('H:i', strtotime($event['StartTime']))) ?>, <?= htmlspecialchars($event['ClassType']) ?>
                                </div>

                                <div style="font-weight: bold; margin-top: 10px;">
                                    <?= htmlspecialchars($event['SubjectName']) ?>
                                </div>

                                <div style="margin-top: 2px;">
                                    (<?= htmlspecialchars($event['Room']) ?>)
                                </div>

                                <?php if ($event['WeekType'] !== 'every'): ?>
                                    <div style="font-size: 9px; color: #d9534f; margin-top: 2px;">
                                        <?= $event['WeekType'] === 'even' ? 'Tydz. parzysty' : 'Tydz. nieparzysty' ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>