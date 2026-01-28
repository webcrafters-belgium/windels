<?php
require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /pages/account/login?referer=/admin/");
    exit;
}

$username = $_SESSION['user']['name'] ?? 'admin';
$currentUserId = $_SESSION['user']['id'] ?? 0;
$currentRole = $_SESSION['user']['role'] ?? 'user';

include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php';

$todoFile = $_SERVER['DOCUMENT_ROOT'] . '/todo.php';
$todos = require $todoFile;

$sql = "SELECT * FROM admin_pages WHERE visibility = 'visible' ORDER BY display_order ASC";
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

<div class="min-h-screen bg-[#0d0d0d] text-gray-200">

    <!-- TOP HEADER -->
    <div class="px-8 py-6 border-b border-gray-800 bg-[#121212] flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold">Welkom terug, <?= htmlspecialchars($username) ?> 👋</h1>
            <p class="text-gray-400 text-sm">Beheer jouw volledige WindelsGreen webshop.</p>
        </div>

        <a href="/admin/pages/manage_admin_pages/add.php"
           class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg font-semibold">
            + nieuwe adminpagina
        </a>
    </div>

    <div class="flex">

        <!-- SIDEBAR -->
        <aside class="hidden md:block w-64 bg-[#141414] border-r border-gray-800 min-h-screen p-6">
            <h2 class="text-sm uppercase tracking-wider text-gray-500">Navigatie</h2>
            <ul class="mt-4 space-y-2">
                <li><a href="/admin/pages/products" class="block p-2 rounded hover:bg-gray-800"><i class="bi bi-box-seam mr-2"></i> Producten</a></li>
                <li><a href="/admin/pages/orders" class="block p-2 rounded hover:bg-gray-800"><i class="bi bi-receipt mr-2"></i> Bestellingen</a></li>
                <li><a href="/admin/pages/customers" class="block p-2 rounded hover:bg-gray-800"><i class="bi bi-people mr-2"></i> Klanten</a></li>
                <li><a href="/admin/pages/settings" class="block p-2 rounded hover:bg-gray-800"><i class="bi bi-gear mr-2"></i> Instellingen</a></li>
                <li><a href="/admin/" class="block p-2 rounded hover:bg-gray-800"><i class="bi bi-house mr-2"></i>Home</a></li>
            </ul>
        </aside>

        <!-- MAIN -->
        <main class="flex-1 p-8 space-y-12">

            <!-- QUICK WIDGETS -->
            <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                <a class="block p-6 bg-[#1a1a1a] border border-gray-800 rounded-2xl hover:bg-gray-900 transition">
                    <i class="bi bi-box-seam text-4xl text-green-500"></i>
                    <h3 class="mt-4 text-xl font-semibold">Producten</h3>
                    <p class="text-gray-400 text-sm">Beheer alle producten en varianten.</p>
                </a>

                <a class="block p-6 bg-[#1a1a1a] border border-gray-800 rounded-2xl hover:bg-gray-900 transition">
                    <i class="bi bi-receipt text-4xl text-green-500"></i>
                    <h3 class="mt-4 text-xl font-semibold">Bestellingen</h3>
                    <p class="text-gray-400 text-sm">Inzage in betaalde/afwachting orders.</p>
                </a>

                <a class="block p-6 bg-[#1a1a1a] border border-gray-800 rounded-2xl hover:bg-gray-900 transition">
                    <i class="bi bi-people text-4xl text-green-500"></i>
                    <h3 class="mt-4 text-xl font-semibold">Klanten</h3>
                    <p class="text-gray-400 text-sm">Alle klantaccounts en gegevens.</p>
                </a>

                <a class="block p-6 bg-[#1a1a1a] border border-gray-800 rounded-2xl hover:bg-gray-900 transition">
                    <i class="bi bi-gear text-4xl text-green-500"></i>
                    <h3 class="mt-4 text-xl font-semibold">Instellingen</h3>
                    <p class="text-gray-400 text-sm">Websitegegevens en configuratie.</p>
                </a>
            </section>

            <!-- ADMIN PAGE SECTIONS (CATEGORIZED) -->
            <section class="space-y-10">

                <?php foreach ($pages_by_section as $section => $pages): ?>
                    <div>
                        <h2 class="text-2xl font-bold mb-4"><?= htmlspecialchars($section) ?></h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            <?php foreach ($pages as $page): ?>
                                <a href="<?= htmlspecialchars($page['url']) ?>"
                                   class="block p-6 bg-[#1a1a1a] border border-gray-800 hover:border-green-500 rounded-2xl hover:bg-gray-900 transition">
                                    <div class="flex items-center">
                                        <i class="bi <?= htmlspecialchars($page['icon_class']) ?> text-3xl text-green-500 mr-4"></i>
                                        <h4 class="text-lg font-semibold"><?= htmlspecialchars($page['title']) ?></h4>
                                    </div>
                                    <?php if (!empty($page['description'])): ?>
                                        <p class="text-gray-400 text-sm mt-3"><?= htmlspecialchars($page['description']) ?></p>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <?php
                $blogCount = $conn->query("SELECT COUNT(*) FROM blog_posts")->fetch_row()[0];
                ?>

                <a href="/admin/pages/blogs"
                   class="block p-6 bg-[#1a1a1a] border border-gray-800 rounded-2xl hover:bg-gray-900 transition">
                    <i class="bi bi-journal-text text-4xl text-green-500"></i>
                    <h3 class="mt-4 text-xl font-semibold">Blogs</h3>
                    <p class="text-gray-400 text-sm"><?= (int)$blogCount ?> blogposts</p>
                </a>


            </section>

            <!-- TODO MANAGER -->
            <section class="bg-[#141414] border border-gray-800 rounded-2xl p-8 shadow-xl">
                <h2 class="text-2xl font-bold mb-2"><i class="bi bi-check2-square text-green-500 mr-2"></i> TODO Manager</h2>
                <p class="text-gray-400 text-sm mb-6">Taken beheren en afvinken.</p>

                <?php foreach (['short_term' => 'Korte termijn', 'long_term' => 'Lange termijn'] as $sectionKey => $sectionLabel): ?>
                    <details class="mb-6 bg-[#1a1a1a] border border-gray-800 rounded-xl p-5 group" open>
                        <summary class="cursor-pointer flex justify-between items-center">
                            <span class="font-semibold text-lg"><?= htmlspecialchars($sectionLabel) ?></span>
                            <i class="bi bi-chevron-down text-gray-400 group-open:rotate-180 transition"></i>
                        </summary>

                        <div class="mt-4 space-y-4">
                            <?php foreach ($todos[$sectionKey] as $category => $tasks): ?>
                                <div class="p-4 bg-[#0f0f0f] border border-gray-800 rounded-xl">
                                    <h3 class="font-semibold mb-3"><?= htmlspecialchars($category) ?></h3>

                                    <?php foreach ($tasks as $task): ?>
                                        <label class="flex items-center justify-between p-2 border-b border-gray-800 last:border-0">
                                            <span><?= htmlspecialchars($task['title']) ?></span>
                                            <input type="checkbox"
                                                   class="todo-checkbox h-5 w-5 accent-green-600"
                                                   data-title="<?= htmlspecialchars($task['title']) ?>"
                                                    <?= $task['status'] === 'done' ? 'checked' : '' ?>>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </details>
                <?php endforeach; ?>

            </section>


            <!-- CHANGELOG -->
            <section class="bg-[#1a1a1a] border border-gray-800 rounded-2xl p-6">
                <h2 class="text-xl font-bold mb-3"><i class="bi bi-clock-history text-green-500 mr-2"></i> Changelog</h2>
                <ul class="text-gray-400 text-sm space-y-1">
                    <li>🆕 2025-06-29 – Nieuwe donkere admin UI.</li>
                    <li>🆕 2025-06-29 – Categorieblokken toegevoegd.</li>
                    <li>🔧 2025-06-28 – Verbeterde taakmanager.</li>
                </ul>
            </section>

        </main>

    </div>

</div>



<script>
    document.querySelectorAll(".todo-checkbox").forEach(cb => {
        cb.addEventListener("change", async (e) => {
            const title = e.target.dataset.title;
            const status = e.target.checked ? "done" : "open";

            const res = await fetch("todo/update.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ title, status })
            });

            try {
                await res.json();
            } catch {}
        });
    });
</script>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'; ?>
