<?php

global $conn;

require $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /pages/account/login?referer=/admin/");
    exit;
}

$username = $_SESSION['user']['name'] ?? 'admin';
$currentUserId = $_SESSION['user']['id'] ?? 0;
$currentRole = $_SESSION['user']['role'] ?? 'user';

include $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';

$todoFile = $_SERVER['DOCUMENT_ROOT'] . '/todo.php';
$todos = file_exists($todoFile) ? require $todoFile : ['short_term' => [], 'long_term' => []];

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

<div class="min-h-screen pt-20 pb-12 text-gray-200">

    <!-- TOP HEADER -->
    <div class="max-w-7xl mx-auto px-6 mb-10 animate-fadeInUp">
        <div class="card-glass p-8 flex justify-between items-center">
            <div>
                <h1 class="text-4xl font-bold mb-2 glow-text">Welkom terug, <span class="bg-gradient-to-r from-teal-400 to-cyan-400 bg-clip-text text-transparent"><?= htmlspecialchars($username) ?></span> 👋</h1>
                <p class="text-gray-400">Beheer jouw volledige WindelsGreen webshop.</p>
            </div>

            <a href="/admin/pages/manage_admin_pages/add.php"
               class="accent-bg px-5 py-3 rounded-xl font-semibold text-white hover:opacity-90 transition flex items-center space-x-2">
                <i class="bi bi-plus-circle"></i>
                <span>Nieuwe adminpagina</span>
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-6 flex gap-8">

        <!-- SIDEBAR -->
        <aside class="hidden md:block w-64 glass rounded-2xl p-6 h-fit sticky top-24">
            <h2 class="text-xs uppercase tracking-widest text-teal-400 font-semibold mb-4">Navigatie</h2>
            <ul class="space-y-2">
                <li><a href="/admin/pages/products/" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/5 transition"><i class="bi bi-box-seam-fill text-xl text-teal-400"></i><span>Producten</span></a></li>
                <li><a href="/admin/pages/orders/" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/5 transition"><i class="bi bi-receipt text-xl text-blue-400"></i><span>Bestellingen</span></a></li>
                <li><a href="/admin/pages/customers/" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/5 transition"><i class="bi bi-people-fill text-xl text-violet-400"></i><span>Klanten</span></a></li>
                <li><a href="/admin/pages/settings/" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/5 transition"><i class="bi bi-gear-fill text-xl text-slate-400"></i><span>Instellingen</span></a></li>
                <li><a href="/admin/" class="flex items-center space-x-3 p-3 rounded-xl hover:bg-white/5 transition"><i class="bi bi-house-fill text-xl text-amber-400"></i><span>Home</span></a></li>
            </ul>
        </aside>

        <!-- MAIN -->
        <main class="flex-1 space-y-10">

            <!-- QUICK WIDGETS -->
            <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 stagger-children">
                <a class="card-glass block p-6 group cursor-pointer" href="/admin/pages/products/">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500/30 to-emerald-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="bi bi-box-seam-fill text-2xl text-teal-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Producten</h3>
                    <p class="text-gray-400 text-sm mt-1">Beheer alle producten en varianten.</p>
                </a>

                <a class="card-glass block p-6 group cursor-pointer" href="/admin/pages/orders/">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="bi bi-receipt text-2xl text-blue-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Bestellingen</h3>
                    <p class="text-gray-400 text-sm mt-1">Inzage in betaalde/afwachting orders.</p>
                </a>

                <a class="card-glass block p-6 group cursor-pointer" href="/admin/pages/customers/">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="bi bi-people-fill text-2xl text-violet-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Klanten</h3>
                    <p class="text-gray-400 text-sm mt-1">Alle klantaccounts en gegevens.</p>
                </a>

                <a class="card-glass block p-6 group cursor-pointer" href="/admin/pages/settings/">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-500/30 to-gray-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="bi bi-gear-fill text-2xl text-slate-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Instellingen</h3>
                    <p class="text-gray-400 text-sm mt-1">Websitegegevens en configuratie.</p>
                </a>
            </section>

            <!-- ADMIN PAGE SECTIONS (CATEGORIZED) -->
            <section class="space-y-8">

                <?php foreach ($pages_by_section as $section => $pages): ?>
                    <div class="animate-fadeInUp">
                        <h2 class="text-2xl font-bold mb-5 flex items-center">
                            <span class="w-2 h-2 rounded-full bg-teal-400 mr-3"></span>
                            <?= htmlspecialchars($section) ?>
                        </h2>

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                            <?php foreach ($pages as $page): ?>
                                <a href="<?= htmlspecialchars($page['url']) ?>"
                                   class="card-glass block p-6 group">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500/20 to-cyan-500/20 flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                                            <i class="bi <?= htmlspecialchars($page['icon_class']) ?> text-xl text-teal-400"></i>
                                        </div>
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
                   class="card-glass block p-6 group max-w-sm">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500/30 to-pink-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="bi bi-journal-text text-2xl text-rose-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold">Blogs</h3>
                    <p class="text-gray-400 text-sm mt-1"><?= (int)$blogCount ?> blogposts</p>
                </a>


            </section>

            <!-- TODO MANAGER -->
            <section class="card-glass p-8">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/30 to-green-500/30 flex items-center justify-center">
                        <i class="bi bi-check2-square text-xl text-emerald-400"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold">TODO Manager</h2>
                        <p class="text-gray-400 text-sm">Taken beheren en afvinken.</p>
                    </div>
                </div>

                <?php foreach (['short_term' => 'Korte termijn', 'long_term' => 'Lange termijn'] as $sectionKey => $sectionLabel): ?>
                    <details class="mb-4 glass rounded-xl p-5 group" open>
                        <summary class="cursor-pointer flex justify-between items-center font-semibold text-lg">
                            <span><?= htmlspecialchars($sectionLabel) ?></span>
                            <i class="bi bi-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                        </summary>

                        <div class="mt-4 space-y-4">
                            <?php foreach ($todos[$sectionKey] as $category => $tasks): ?>
                                <div class="p-4 bg-black/20 rounded-xl">
                                    <h3 class="font-semibold mb-3 text-teal-400"><?= htmlspecialchars($category) ?></h3>

                                    <?php foreach ($tasks as $task): ?>
                                        <label class="flex items-center justify-between p-2 border-b border-gray-800/50 last:border-0 hover:bg-white/5 rounded transition">
                                            <span class="<?= $task['status'] === 'done' ? 'line-through text-gray-500' : '' ?>"><?= htmlspecialchars($task['title']) ?></span>
                                            <input type="checkbox"
                                                   class="todo-checkbox h-5 w-5 accent-teal-500 rounded"
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
            <section class="card-glass p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-amber-500/30 to-orange-500/30 flex items-center justify-center">
                        <i class="bi bi-clock-history text-lg text-amber-400"></i>
                    </div>
                    <h2 class="text-xl font-bold">Changelog</h2>
                </div>
                <ul class="text-gray-400 text-sm space-y-2">
                    <li class="flex items-center space-x-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span><span>2025-06-29 – Moderne glassmorphism UI update.</span></li>
                    <li class="flex items-center space-x-2"><span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span><span>2025-06-29 – Categorieblokken toegevoegd.</span></li>
                    <li class="flex items-center space-x-2"><span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span><span>2025-06-28 – Verbeterde taakmanager.</span></li>
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

<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
