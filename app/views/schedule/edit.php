<?php
/** @var array $subjects */
/** @var array $events */

$days = [1 => 'Poniedziałek', 2 => 'Wtorek', 3 => 'Środa', 4 => 'Czwartek', 5 => 'Piątek', 6 => 'Sobota', 7 => 'Niedziela'];
?>

<div class="main-content">

    <div class="page-header" style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Zarządzaj Planem Zajęć</h1>
            <p>Dodawaj bloki zajęciowe do aktywnego semestru</p>
        </div>
        <a href="/schedule" class="btn-primary" style="background: #6c757d; text-decoration: none; padding: 10px 15px; color: white; border-radius: 4px;">Wróć do kalendarza</a>
    </div>

    <?php if (empty($subjects)): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 4px;">
            Musisz najpierw dodać jakieś przedmioty do aktywnego semestru (w zakładce Przedmioty), aby móc układać plan!
        </div>
    <?php else: ?>
        <div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 6px; margin-bottom: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3 style="margin-top: 0;">Dodaj nowy blok zajęć</h3>
            <form method="post" action="/schedule/add-event" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; align-items: end;">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                <div>
                    <label style="display:block; font-size: 0.9em; margin-bottom: 5px;">Przedmiot</label>
                    <select name="subject_id" style="width: 100%; padding: 8px;" required>
                        <?php foreach ($subjects as $sub): ?>
                            <option value="<?= $sub['ID'] ?>"><?= htmlspecialchars($sub['Name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size: 0.9em; margin-bottom: 5px;">Dzień</label>
                    <select name="day_of_week" style="width: 100%; padding: 8px;" required>
                        <?php foreach ($days as $num => $name): ?>
                            <option value="<?= $num ?>"><?= $name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size: 0.9em; margin-bottom: 5px;">Typ</label>
                    <select name="class_type" style="width: 100%; padding: 8px;">
                        <option value="WYK">Wykład</option>
                        <option value="LAB">Laboratoria</option>
                        <option value="CW">Ćwiczenia</option>
                        <option value="PROJ">Projekt</option>
                    </select>
                </div>

                <div>
                    <label style="display:block; font-size: 0.9em; margin-bottom: 5px;">Od</label>
                    <input type="time" name="start_time" style="width: 100%; padding: 8px;" required>
                </div>

                <div>
                    <label style="display:block; font-size: 0.9em; margin-bottom: 5px;">Do</label>
                    <input type="time" name="end_time" style="width: 100%; padding: 8px;" required>
                </div>

                <div>
                    <label style="display:block; font-size: 0.9em; margin-bottom: 5px;">Sala</label>
                    <input type="text" name="room" placeholder="np. Aula C" style="width: 100%; padding: 8px;" required>
                </div>

                <div>
                    <label style="display:block; font-size: 0.9em; margin-bottom: 5px;">Częstotliwość</label>
                    <select name="week_type" style="width: 100%; padding: 8px;">
                        <option value="every">Co tydzień</option>
                        <option value="even">Tygodnie parzyste</option>
                        <option value="odd">Tygodnie nieparzyste</option>
                    </select>
                </div>

                <div>
                    <button type="submit" style="width: 100%; padding: 9px; background: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer;">Dodaj</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <div style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 6px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
        <h3 style="margin-top: 0;">Obecne bloki zajęciowe w planie</h3>

        <?php if (empty($events)): ?>
            <p style="color: #666;">Brak zajęć. Dodaj coś w formularzu powyżej.</p>
        <?php else: ?>
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                <tr style="background: #f1f1f1;">
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">Dzień</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">Godziny</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">Przedmiot</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">Typ / Sala</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd;">Kiedy</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; width: 60px;">Akcja</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($events as $event): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px; font-weight: bold;"><?= $days[$event['DayOfWeek']] ?></td>
                        <td style="padding: 10px; color: #555;">
                            <?= htmlspecialchars(date('H:i', strtotime($event['StartTime']))) ?> - <?= htmlspecialchars(date('H:i', strtotime($event['EndTime']))) ?>
                        </td>
                        <td style="padding: 10px;"><?= htmlspecialchars($event['SubjectName']) ?></td>
                        <td style="padding: 10px;">
                            <span style="background: #e9ecef; padding: 2px 6px; border-radius: 4px; font-size: 0.8em; margin-right: 5px;"><?= htmlspecialchars($event['ClassType']) ?></span>
                            <?= htmlspecialchars($event['Room']) ?>
                        </td>
                        <td style="padding: 10px;">
                            <?php
                            if ($event['WeekType'] === 'even') echo 'Parzyste';
                            elseif ($event['WeekType'] === 'odd') echo 'Nieparzyste';
                            else echo 'Co tydzień';
                            ?>
                        </td>
                        <td style="padding: 10px;">
                            <form method="post" action="/schedule/delete-event" style="margin: 0;" onsubmit="return confirm('Usunąć te zajęcia z planu?');">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="event_id" value="<?= $event['ID'] ?>">
                                <button type="submit" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.9em;">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>