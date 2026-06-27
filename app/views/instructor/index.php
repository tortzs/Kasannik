<?php
/** @var Int $userId */
/** @var array $instructors */


?>
Prowadzący
<?php if (!empty($instructors)) { ?>
    <table>
        <thead>
        <tr>
            <th>
                Tytuł
            </th>
            <th>
                Imie i nazwisko
            </th>
            <th>
                Mail
            </th>
            <th>
                Pokój
            </th>
            <th>
                Akcje
            </th>
        </tr>
        </thead>
        <tbody>
        <?php

        foreach ($instructors as $instructor) {
            ?>
            <tr>
                <td>
                    <?php echo $instructor['AcademicTitle']; ?>
                </td>
                <td>
                    <?php echo $instructor['FirstName'] . ' ' . $instructor['LastName']; ?>
                </td>
                <td>
                    <a href="mailto:<?php echo $instructor['Email']; ?>">
                        <?php echo $instructor['Email']; ?>
                    </a>
                </td>
                <td>
                    <?php echo $instructor['Room']; ?>
                </td>
                <td>
                    <a href="instructor/edit/<?php echo $instructor['ID']?>">
                        Edytuj
                    </a>
                    <form method="post" action="/instructor/delete" onsubmit="return confirm('Na pewno usunąć prowadzącego?');">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                        <input type="hidden" name="instructorId" value="<?= (int)$instructor['ID'] ?>">

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

<a href="/instructor/add">
    Dodaj instruktora
</a>