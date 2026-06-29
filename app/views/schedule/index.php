<?php
/** @var array $events */
/** @var array|null $semester */

// Definiujemy ramy czasowe siatki (od 7:00 do 18:00)
$startHour = 7;
$endHour = 18;

// Dni robocze
$daysOfWeek = [
    1 => 'Poniedziałek', 2 => 'Wtorek', 3 => 'Środa', 4 => 'Czwartek', 5 => 'Piątek', 6 => 'Sobota', 7 => 'Niedziela'
];

$groupedEvents = [];
if (!empty($events)) {
    foreach ($events as $event) {
        $groupedEvents[$event['DayOfWeek']][] = $event;
    }
}
?>
<div class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon"><i class="fa-regular fa-calendar-days"></i></div>
        <div>
            <h1>Plan Zajęć</h1>
            <?php if ($semester): ?>
                <p id="semester-boundary" data-start="<?= htmlspecialchars($semester['StartDate']) ?>" data-end="<?= htmlspecialchars($semester['EndDate']) ?>">
                    Aktywny semestr: <strong class="text-dark"><?= htmlspecialchars($semester['Name']) ?></strong>
                    (Od <?= htmlspecialchars(date('d.m.Y', strtotime($semester['StartDate']))) ?> do <?= htmlspecialchars(date('d.m.Y', strtotime($semester['EndDate']))) ?>)
                </p>
            <?php else: ?>
                <p style="color: var(--color-pink); font-weight: 600;">Brak aktywnego semestru!</p>
            <?php endif; ?>
        </div>
    </div>
    <a href="/schedule/edit" class="btn-primary" style="text-decoration: none;">
        <i class="fa-solid fa-pen"></i> Edytuj plan
    </a>
</div>

<div class="card full-width" style="padding: 0; overflow-x: auto; border-radius: 16px;">
    <div class="timetable-wrapper" style="display: flex; background: white; min-width: 950px; position: relative;">

        <div class="time-labels" style="width: 60px; border-right: 1px solid var(--border-color-light); background: #fdfdfd; flex-shrink: 0;">
            <div style="height: 50px; border-bottom: 1px solid var(--border-color-light);"></div> 
            <?php for ($h = $startHour; $h < $endHour; $h++): ?>
                <div style="height: 60px; text-align: right; padding-right: 10px; border-bottom: 1px solid var(--border-color-light); box-sizing: border-box; position: relative;">
                    <span style="position: absolute; top: -10px; right: 10px; font-size: 0.75rem; font-weight: 600; color: var(--text-gray);">
                        <?= $h ?>:00
                    </span>
                </div>
            <?php endfor; ?>
        </div>

        <?php foreach ($daysOfWeek as $dayNum => $dayName): ?>
            <div class="day-col" style="flex: 1; border-right: 1px solid var(--border-color-light); position: relative;">

                <div style="text-align: center; background: #fdfdfd; color: var(--text-dark); padding: 15px 0; font-size: 0.95rem; font-weight: 700; height: 50px; border-bottom: 1px solid var(--border-color-light); box-sizing: border-box;">
                    <?= $dayName ?>
                </div>

                <div class="day-grid" style="position: relative; height: <?= ($endHour - $startHour) * 60 ?>px; background-image: repeating-linear-gradient(to bottom, transparent, transparent 59px, var(--border-color-light) 60px);">

                    <?php if (!empty($groupedEvents[$dayNum])): ?>
                        <?php foreach ($groupedEvents[$dayNum] as $event): ?>
                            <?php
                            $startParts = explode(':', $event['StartTime']);
                            $topPx = (($startParts[0] - $startHour) * 60) + $startParts[1];

                            $endParts = explode(':', $event['EndTime']);
                            $duration = (($endParts[0] * 60) + $endParts[1]) - (($startParts[0] * 60) + $startParts[1]);

                            $isLecture = stripos($event['ClassType'], 'wyk') !== false;
                            
                            $bgColor = $isLecture ? 'var(--color-purple-light)' : 'var(--sidebar-bg)';
                            $borderColor = $isLecture ? 'var(--color-purple)' : 'var(--primary)';
                            $textColor = $isLecture ? 'var(--color-purple)' : 'var(--primary-dark)';
                            ?>

                            <div style="position: absolute; top: <?= $topPx ?>px; height: <?= $duration ?>px; left: 4%; width: 92%; background: <?= $bgColor ?>; border-left: 4px solid <?= $borderColor ?>; border-radius: 8px; box-sizing: border-box; padding: 8px 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.04); display: flex; flex-direction: column; cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 15px rgba(0,0,0,0.08)'; this.style.zIndex='10';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 10px rgba(0,0,0,0.04)'; this.style.zIndex='5';" style="z-index: 5;">
                                
                                <div style="color: <?= $textColor ?>; font-size: 0.7rem; font-weight: 700; margin-bottom: 4px; display: flex; justify-content: space-between; align-items: center;">
                                    <span><i class="fa-regular fa-clock" style="margin-right: 3px;"></i><?= htmlspecialchars(date('H:i', strtotime($event['StartTime']))) ?></span>
                                    <span style="background: rgba(255,255,255,0.6); padding: 2px 6px; border-radius: 4px; font-size: 0.65rem;"><?= htmlspecialchars($event['ClassType']) ?></span>
                                </div>

                                <div style="font-weight: 700; font-size: 0.85rem; color: var(--text-dark); line-height: 1.2; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= htmlspecialchars($event['SubjectName']) ?>
                                </div>

                                <div style="font-size: 0.7rem; color: var(--text-gray); font-weight: 600; margin-top: auto; display: flex; justify-content: space-between; align-items: flex-end;">
                                    <span><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($event['Room']) ?></span>
                                    
                                    <?php if (isset($event['WeekType']) && $event['WeekType'] !== 'every'): ?>
                                        <span style="font-size: 0.65rem; color: var(--color-pink); font-weight: 700;">
                                            <?= $event['WeekType'] === 'even' ? 'Tydz. parzysty' : 'Tydz. nieparzysty' ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                            </div>

                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
        
        <div style="clear: both;"></div>
    </div>
</div>
</div>