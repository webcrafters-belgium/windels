<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';

$currentUserId = $_SESSION['user']['id'] ?? 0;
$currentRole = $_SESSION['user']['role'] ?? 'user';

$sql = "SELECT * FROM admin_pages 
        WHERE visibility = 'visible'
        ORDER BY display_order ASC";

$result = $conn->query($sql);

$pages_by_section = [];

while ($page = $result->fetch_assoc()) {
    $belongsToUser = (int)$page['user_id'] === $currentUserId;
    $isGeneralPage = (int)$page['user_id'] === 0 && $page['roles'] === $currentRole;

    if ($belongsToUser || $isGeneralPage) {
        $section = $page['section'] ?? 'Overig';
        $pages_by_section[$section][] = $page;
    }
}
?>

<?php foreach ($pages_by_section as $section => $pages): ?>
    <div class="mb-3">
        <h6 class="text-uppercase text-white-50 mb-2"><?= htmlspecialchars($section) ?></h6>
        <ul class="nav flex-column">
            <?php foreach ($pages as $page): ?>
                <li class="nav-item">
                    <a class="nav-link text-white" href="<?= htmlspecialchars($page['url']) ?>">
                        <i class="bi <?= htmlspecialchars($page['icon_class']) ?>"></i>
                        <?= htmlspecialchars($page['title']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endforeach; ?>
