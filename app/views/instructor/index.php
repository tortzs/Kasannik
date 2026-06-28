<?php
/** @var Int $userId */
/** @var array $instructors */
?>
<div class="main-content">
<div class="page-header">
    <div class="header-title">
        <div class="title-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
        <div>
            <h1>Prowadzący</h1>
            <p>Zarządzaj listą prowadzących i ich informacjami.</p>
        </div>
    </div>
    <a href="/instructor/add" class="btn-outline-primary">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Dodaj prowadzącego
    </a>
</div>

<div class="table-container">
    <div class="table-toolbar">
        <h2>Lista prowadzących</h2>
        <div class="toolbar-actions">
            <div class="search-box">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" placeholder="Szukaj prowadzącego...">
            </div>
            <button class="btn-filter">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
            </button>
        </div>
    </div>

    <?php if (!empty($instructors)) { ?>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tytuł</th>
                    <th>Imię i nazwisko</th>
                    <th>E-mail</th>
                    <th>Pokój</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($instructors as $instructor) { 
                $titleLower = strtolower($instructor['AcademicTitle']);
                $badgeClass = 'badge-teal'; // Domyślny (dr, prof.)
                
                if (strpos($titleLower, 'mgr') !== false) {
                    $badgeClass = 'badge-purple';
                } elseif (strpos($titleLower, 'hab') !== false && strpos($titleLower, 'prof') === false) {
                    $badgeClass = 'badge-pink';
                }
            ?>
                <tr>
                    <td>
                        <div class="title-cell">
                            <span class="badge <?php echo $badgeClass; ?>"><?php echo htmlspecialchars($instructor['AcademicTitle']); ?></span>
                        </div>
                    </td>
                    <td class="fw-bold text-dark">
                        <?php echo htmlspecialchars($instructor['FirstName'] . ' ' . $instructor['LastName']); ?>
                    </td>
                    <td>
                        <a href="mailto:<?php echo htmlspecialchars($instructor['Email']); ?>" class="text-gray email-link">
                            <?php echo htmlspecialchars($instructor['Email']); ?>
                        </a>
                    </td>
                    <td class="fw-bold text-dark">
                        <?php echo htmlspecialchars($instructor['Room']); ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="/instructor/edit/<?php echo $instructor['ID']?>" class="btn-icon edit" title="Edytuj">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                            </a>
                            <form method="post" action="/instructor/delete" onsubmit="return confirm('Na pewno usunąć prowadzącego?');" style="margin: 0;">
                                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                                <input type="hidden" name="instructorId" value="<?= (int)$instructor['ID'] ?>">
                                <button type="submit" class="btn-icon delete" title="Usuń">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        
        <div class="table-pagination">
            <div class="pagination-info">Wyświetlanie <strong>1–<?= count($instructors) ?></strong> z <?= count($instructors) ?></div>
        </div>

    <?php } else { ?>
        <div class="empty-state" style="padding: 40px; text-align: center; color: var(--text-gray);">
            Brak prowadzących w bazie.
        </div>
    <?php } ?>
</div>
</div>