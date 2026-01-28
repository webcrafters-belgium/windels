<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/ini.inc';
session_start();

// Enkel admin
if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] !== 'admin') {
    header("Location: /pages/account/login?referer=/admin/config/");
    exit;
}

include $_SERVER['DOCUMENT_ROOT'] . '/admin/header.php';

// Tijd
$nu = new DateTime('now', new DateTimeZone('Europe/Brussels'));
?>

<div class="min-h-screen bg-[#0d0d0d] text-gray-200 py-10 px-8 space-y-10">

    <!-- PAGE HEADER -->
    <div class="bg-[#141414] border border-gray-800 rounded-2xl p-8 shadow-xl flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold">Webshop Instellingen</h1>
            <p class="text-gray-400 mt-1 text-sm"><?= $nu->format('l d F Y - H:i:s') ?></p>
        </div>
    </div>

    <div class="flex">

        <?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/partials/sidebar.php' ?>;

        <!-- SETTINGS GRID -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">

            <!-- Openingstijden -->
            <div class="bg-[#141414] border border-gray-800 rounded-xl p-6 shadow hover:shadow-lg transition group cursor-pointer">
                <div class="flex flex-col h-full">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="text-green-500 text-3xl group-hover:scale-110 transition">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Openingstijden</h3>
                    </div>
                    <p class="text-gray-400 flex-grow">
                        Standaard openingsuren en uitzonderingen beheren.
                    </p>
                    <a href="/admin/config/opening_times/index.php"
                       class="mt-4 px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-center font-semibold">
                        Beheer tijden
                    </a>
                </div>
            </div>

            <!-- Vakantieperiodes -->
            <div class="bg-[#141414] border border-gray-800 rounded-xl p-6 shadow hover:shadow-lg transition group cursor-pointer">
                <div class="flex flex-col h-full">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="text-yellow-400 text-3xl group-hover:scale-110 transition">
                            <i class="bi bi-calendar-x"></i>
                        </div>
                        <h3 class="text-xl font-semibold">Vakantieperiodes</h3>
                    </div>
                    <p class="text-gray-400 flex-grow">
                        Periodes waarin de winkel afgesloten is.
                    </p>
                    <a href="/admin/config/opening_times/vacation/"
                       class="mt-4 px-4 py-2 bg-yellow-600 hover:bg-yellow-700 rounded-lg text-center font-semibold">
                        Beheer vakanties
                    </a>
                </div>
            </div>

            <!-- Placeholder voor toekomstige instellingen -->
            <div class="bg-[#141414] border border-gray-800 rounded-xl p-6 shadow opacity-60 cursor-not-allowed">
                <div class="flex items-center gap-3 mb-3">
                    <div class="text-gray-500 text-3xl">
                        <i class="bi bi-gear"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-500">Meer Instellingen</h3>
                </div>
                <p class="text-gray-500">
                    Wordt later toegevoegd.
                </p>
            </div>

        </div>
    </div>

</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . '/admin/footer.php'; ?>
