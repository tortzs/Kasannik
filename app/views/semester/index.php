<?php
/** @var Int $userId */
/** @var array $semesters */


?>
Semestry
<?php if (!empty($semesters)) { ?>
    <table>
        <thead>
        <tr>
            <th>
                Nazwa
            </th>
            <th>
                Rozpoczęcie
            </th>
            <th>
                Koniec
            </th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($semesters as $semester) { ?>
            <tr>
                <td>
                    <?php echo $semester['Name']; ?>
                </td>
                <td>
                    <?php echo $semester['StartDate']; ?>
                </td>
                <td>
                    <?php echo $semester['EndDate']; ?>
                </td>
                <td>
                    <a href="/semester/view/<?php echo $semester['ID']?>">
                        Sprawdź
                    </a>
                    <a href="/semester/edit/<?php echo $semester['ID']?>">
                        Edytuj
                    </a>
                    <form method="post" action="/semester/delete" onsubmit="return confirm('Na pewno usunąć prowadzącego?');">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <input type="hidden" name="semesterId" value="<?= (int)$semester['ID'] ?>">

                        <button type="submit">
                            Usuń
                        </button>
                    </form>
                </td>
            </tr>
            <?php
        }
        ?>

        </tbody>
    </table>
<?php } else { ?>
        pusto
<?php } ?>

<a href="/semester/add">
    Dodaj Semestr
</a>