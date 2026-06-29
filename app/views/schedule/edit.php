<?php
/** @var array $subjects */
/** @var array $events */

$days = [1 => 'Poniedziałek', 2 => 'Wtorek', 3 => 'Środa', 4 => 'Czwartek', 5 => 'Piątek', 6 => 'Sobota', 7 => 'Niedziela'];
?>
<div class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon"><i class="fa-solid fa-calendar-plus"></i></div>
        <div>
            <h1>Zarządzaj Planem</h1>
            <p>Dodawaj bloki zajęciowe do aktywnego semestru</p>
        </div>
    </div>
    <a href="/schedule" class="btn-secondary" style="text-decoration: none;">
        <i class="fa-solid fa-arrow-left" style="margin-right: 8px;"></i> Wróć do kalendarza
    </a>
</div>

<?php if (empty($subjects)): ?>
    <div style="background-color: var(--color-pink-light); color: var(--color-pink); padding: 20px; border-radius: 12px; margin-bottom: 30px; font-weight: 600; display: flex; align-items: center; gap: 15px;">
        <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.5rem;"></i>
        Musisz najpierw dodać przedmioty do aktywnego semestru (w zakładce Przedmioty), aby móc układać plan!
    </div>
<?php else: ?>

    <div class="form-card" style="margin-bottom: 30px;">
        <div class="form-header">
            <h2><i class="fa-solid fa-plus text-primary" style="margin-right: 8px;"></i> Dodaj nowy blok zajęć</h2>
        </div>
        
        <div class="form-body">
            <form method="post" action="/schedule/add-event">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">

                <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                    
                    <div class="form-group" style="grid-column: span 2;">
                        <label>Przedmiot</label>
                        <select class="form-control" name="subject_id" required>
                            <option value="" disabled selected>Wybierz przedmiot...</option>
                            <?php foreach ($subjects as $sub): ?>
                                <option value="<?= $sub['ID'] ?>"><?= htmlspecialchars($sub['Name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Dzień</label>
                        <select class="form-control" name="day_of_week" required>
                            <?php foreach ($days as $num => $name): ?>
                                <option value="<?= $num ?>"><?= $name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Typ zajęć</label>
                        <select class="form-control" name="class_type">
                            <option value="WYK">Wykład (WYK)</option>
                            <option value="LAB">Laboratoria (LAB)</option>
                            <option value="CW">Ćwiczenia (CW)</option>
                            <option value="PROJ">Projekt (PROJ)</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Od godziny</label>
                        <input class="form-control" type="time" name="start_time" required>
                    </div>

                    <div class="form-group">
                        <label>Do godziny</label>
                        <input class="form-control" type="time" name="end_time" required>
                    </div>

                    <div class="form-group">
                        <label>Sala</label>
                        <input class="form-control" type="text" name="room" placeholder="np. Aula C" required>
                    </div>

                    <div class="form-group">
                        <label>Częstotliwość</label>
                        <select class="form-control" name="week_type">
                            <option value="every">Co tydzień</option>
                            <option value="even">Tygodnie parzyste</option>
                            <option value="odd">Tygodnie nieparzyste</option>
                        </select>
                    </div>

                </div>

                <div class="form-actions" style="margin-top: 25px; padding: 0;">
                    <button type="submit" class="btn-primary">
                        <i class="fa-solid fa-check"></i> Dodaj do planu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-container">
        <div class="table-toolbar">
            <h2><i class="fa-solid fa-list" style="color: var(--text-gray); margin-right: 8px;"></i> Obecne bloki zajęciowe w planie</h2>
        </div>

        <?php if (empty($events)): ?>
            <div style="padding: 40px; text-align: center; color: var(--text-gray);">
                Brak zajęć w tym semestrze. Dodaj swój pierwszy blok korzystając z formularza powyżej.
            </div>
        <?php else: ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Dzień</th>
                        <th>Godziny</th>
                        <th style="width: 30%;">Przedmiot</th>
                        <th>Typ / Sala</th>
                        <th>Częstotliwość</th>
                        <th style="text-align: center;">Akcja</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td class="fw-bold text-dark"><?= $days[$event['DayOfWeek']] ?></td>
                            
                            <td class="fw-bold text-gray">
                                <?= htmlspecialchars(date('H:i', strtotime($event['StartTime']))) ?> - <?= htmlspecialchars(date('H:i', strtotime($event['EndTime']))) ?>
                            </td>
                            
                            <td class="fw-bold text-dark">
                                <?= htmlspecialchars($event['SubjectName']) ?>
                            </td>
                            
                            <td>
                                <span class="badge badge-purple" style="margin-right: 8px;">
                                    <?= htmlspecialchars($event['ClassType']) ?>
                                </span>
                                <span class="text-gray fw-bold" style="font-size: 0.9rem;">
                                    <i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($event['Room']) ?>
                                </span>
                            </td>
                            
                            <td>
                                <?php if ($event['WeekType'] === 'even'): ?>
                                    <span class="badge badge-pink">Parzyste</span>
                                <?php elseif ($event['WeekType'] === 'odd'): ?>
                                    <span class="badge badge-pink">Nieparzyste</span>
                                <?php else: ?>
                                    <span class="badge badge-teal">Co tydzień</span>
                                <?php endif; ?>
                            </td>
                            
                            <td>
                                <div class="action-buttons">
                                    <form method="post" action="/schedule/delete-event" style="margin: 0;" onsubmit="return confirm('Na pewno chcesz usunąć te zajęcia z planu?');">
                                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                        <input type="hidden" name="event_id" value="<?= (int)$event['ID'] ?>">
                                        <button type="submit" class="btn-icon delete" title="Usuń z planu">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?php endif; ?>
</div>