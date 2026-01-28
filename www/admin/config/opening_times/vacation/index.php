<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /pages/account/login");
    exit;
}

// Toevoegen nieuwe vakantieperiode
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
    $title      = $_POST['title'];
    $start_date = $_POST['start_date'];
    $end_date   = $_POST['end_date'];
    $note       = $_POST['note'];

    if ($title && $start_date && $end_date) {
        $stmt = $conn->prepare("INSERT INTO vacation_periods (title, start_date, end_date, note) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $title, $start_date, $end_date, $note);
        $stmt->execute();
        header("Location: ?success=1");
        exit;
    }
}

// Ophalen vakanties
$result = $conn->query("SELECT * FROM vacation_periods ORDER BY start_date DESC");

include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php';
?>

<div class="min-h-screen bg-[#0d0d0d] text-gray-200 flex">

    <!-- SIDEBAR -->
    <aside class="w-64 hidden md:block border-r border-gray-800 bg-[#111]">
        <?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/partials/sidebar.php'; ?>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 p-10 space-y-10">

        <!-- HEADER -->
        <div class="bg-[#141414] border border-gray-800 rounded-2xl p-8 shadow-xl">
            <h1 class="text-3xl font-bold">Vakantieperiodes</h1>
            <p class="text-gray-400 mt-1">Beheer de periodes waarin de webshop gesloten is</p>

            <?php if (isset($_GET['success'])): ?>
                <div class="mt-4 px-4 py-3 bg-green-900/40 border border-green-700 text-green-300 rounded-lg">
                    Vakantieperiode succesvol toegevoegd.
                </div>
            <?php endif; ?>
        </div>


        <!-- FORM CARD -->
        <div class="bg-[#141414] border border-gray-800 rounded-2xl p-8 shadow-xl">
            <h2 class="text-xl font-bold mb-6">Nieuwe vakantieperiode toevoegen</h2>

            <form method="post" class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="flex flex-col">
                    <label class="text-sm text-gray-400">Titel</label>
                    <input type="text" name="title"
                           class="bg-[#0f0f0f] border border-gray-800 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-600"
                           required>
                </div>

                <div class="flex flex-col">
                    <label class="text-sm text-gray-400">Startdatum</label>
                    <input type="date" name="start_date"
                           class="bg-[#0f0f0f] border border-gray-800 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-600"
                           required>
                </div>

                <div class="flex flex-col">
                    <label class="text-sm text-gray-400">Einddatum</label>
                    <input type="date" name="end_date"
                           class="bg-[#0f0f0f] border border-gray-800 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-600"
                           required>
                </div>

                <div class="md:col-span-2 flex flex-col">
                    <label class="text-sm text-gray-400">Opmerking (optioneel)</label>
                    <textarea name="note" rows="2"
                              class="bg-[#0f0f0f] border border-gray-800 px-3 py-2 rounded-lg focus:ring-2 focus:ring-green-600"></textarea>
                </div>

                <div class="flex items-end">
                    <button class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg font-semibold">
                        Toevoegen
                    </button>
                </div>

            </form>
        </div>


        <!-- TABLE -->
        <div class="bg-[#141414] border border-gray-800 rounded-2xl p-8 shadow-xl">

            <h2 class="text-xl font-bold mb-6">Bestaande vakantieperiodes</h2>

            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">

                    <thead>
                    <tr class="text-gray-400 text-sm border-b border-gray-800">
                        <th class="pb-3">Titel</th>
                        <th class="pb-3">Periode</th>
                        <th class="pb-3">Opmerking</th>
                        <th class="pb-3">Actie</th>
                    </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-800">

                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="hover:bg-[#1a1a1a] transition">

                            <td class="py-4"><?= htmlspecialchars($row['title']) ?></td>

                            <td class="py-4">
                                <?= date('d/m/Y', strtotime($row['start_date'])) ?>
                                —
                                <?= date('d/m/Y', strtotime($row['end_date'])) ?>
                            </td>

                            <td class="py-4 text-gray-300">
                                <?= nl2br(htmlspecialchars($row['note'])) ?>
                            </td>

                            <td class="py-4">

                                <form method="post"
                                      action="delete_vacation.php"
                                      onsubmit="return confirm('Verwijderen?');">

                                    <input type="hidden" name="id" value="<?= $row['id'] ?>">

                                    <button class="px-3 py-1 bg-red-700 hover:bg-red-800 rounded-lg text-sm">
                                        🗑️ Verwijder
                                    </button>

                                </form>

                            </td>

                        </tr>
                    <?php endwhile; ?>

                    </tbody>

                </table>
            </div>
        </div>

    </main>
</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'; ?>
