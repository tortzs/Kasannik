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
                    // foreach ($exams as $exam): 
                    ?>
                    <div class="list-item">
                        <div class="item-icon pink"><i class="fa-regular fa-calendar"></i></div>
                        <div class="item-details">
                            <div class="item-title pink-text">Matematyka Dyskretna</div>
                            <div class="item-desc">Kolos – Oblać</div>
                        </div>
                        <div class="item-meta">
                            <div class="item-date">23.05.2024<br>(czw.)</div>
                            <div class="item-badge pink-bg">Za 2 dni</div>
                        </div>
                    </div>
                    <?php 
                    // endforeach; 
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
                    // foreach ($todos as $todo): 
                    ?>
                    <label class="todo-item">
                        <input type="checkbox">
                        <span class="todo-text">Spotkanie kierunkowe</span>
                        <span class="priority-badge high">Wysoki <i class="fa-regular fa-star"></i></span>
                    </label>
                    <label class="todo-item">
                        <input type="checkbox">
                        <span class="todo-text">Sprzedać Szymona</span>
                        <span class="priority-badge medium">Średni <div class="dot green"></div></span>
                    </label>
                    <label class="todo-item done">
                        <input type="checkbox" checked>
                        <span class="todo-text">Pozbawić Torta włosów</span>
                    </label>
                    <?php 
                    // endforeach; 
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