<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/header.php';
?>

<!-- PAGE HEADER -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold mb-2 glow-text" style="color: var(--text-primary);">
                <i class="bi bi-building accent-primary mr-3"></i>Kantoor Dashboard
            </h1>
            <p class="text-lg" style="color: var(--text-muted);">Beheer kantooractiviteiten en HR-functies</p>
        </div>
    </div>
</div>

<!-- KANTOOR MENU CARDS -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 stagger-children">
    
    <a href="/index.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500/30 to-cyan-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-house-fill text-2xl text-teal-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Home</h3>
        <p class="text-sm" style="color: var(--text-muted);">Ga naar de hoofdpagina</p>
    </a>
    
    <a href="/andy/send_message.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500/30 to-indigo-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-envelope-fill text-2xl text-blue-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Berichten</h3>
        <p class="text-sm" style="color: var(--text-muted);">Interne communicatie</p>
    </a>
    
    <a href="/andy/kantoor/calendar.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-violet-500/30 to-purple-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-calendar3 text-2xl text-violet-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Kalender</h3>
        <p class="text-sm" style="color: var(--text-muted);">Planning en afspraken</p>
    </a>
    
    <a href="/andy/user_reports.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500/30 to-green-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-clock-history text-2xl text-emerald-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Klok In/Out Rapport</h3>
        <p class="text-sm" style="color: var(--text-muted);">Aanwezigheidsregistratie</p>
    </a>
    
    <a href="/andy/admin_rooster.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500/30 to-orange-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-calendar-week text-2xl text-amber-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Uurrooster</h3>
        <p class="text-sm" style="color: var(--text-muted);">Werkschema beheren</p>
    </a>
    
    <a href="/andy/manage_payslips.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500/30 to-pink-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-cash-stack text-2xl text-rose-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Loonfiche Maken</h3>
        <p class="text-sm" style="color: var(--text-muted);">Salarisbeheer</p>
    </a>
    
    <a href="/andy/rss/manage.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500/30 to-sky-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-rss text-2xl text-cyan-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">RSS</h3>
        <p class="text-sm" style="color: var(--text-muted);">Nieuwsfeed beheren</p>
    </a>
    
    <a href="/andy/rss_balk/manage.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500/30 to-violet-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-layout-text-sidebar text-2xl text-indigo-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">RSS Balk</h3>
        <p class="text-sm" style="color: var(--text-muted);">Ticker beheren</p>
    </a>
    
    <a href="/andy/kantoor/kortingen/index.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-red-500/30 to-rose-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-percent text-2xl text-red-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Korting/Acties</h3>
        <p class="text-sm" style="color: var(--text-muted);">Kortingsacties beheren</p>
    </a>
    
    <a href="/andy/kantoor/kortingen/korting.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-orange-500/30 to-amber-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-tags-fill text-2xl text-orange-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Kortingenlijst Winkel</h3>
        <p class="text-sm" style="color: var(--text-muted);">Actieve kortingen overzicht</p>
    </a>
    
    <a href="/andy/kantoor/kortingen/viewimage-weekdeal.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500/30 to-amber-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-display text-2xl text-yellow-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Weekdeal Scherm</h3>
        <p class="text-sm" style="color: var(--text-muted);">Promotie display</p>
    </a>
    
    <a href="/andy/kantoor/tasks_overview.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-lime-500/30 to-green-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-list-task text-2xl text-lime-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Taken</h3>
        <p class="text-sm" style="color: var(--text-muted);">Taakbeheer</p>
    </a>
    
    <a href="/andy/kantoor/cash_count.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500/30 to-emerald-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-cash-coin text-2xl text-green-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Kassa Beheer</h3>
        <p class="text-sm" style="color: var(--text-muted);">Kassaregistratie</p>
    </a>
    
    <a href="/andy/kantoor/boekhouding/index.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-500/30 to-gray-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-journal-bookmark text-2xl text-slate-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Boekhouding</h3>
        <p class="text-sm" style="color: var(--text-muted);">Financieel overzicht</p>
    </a>
    
    <a href="/andy/gdpr_admin.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500/30 to-violet-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-shield-check text-2xl text-purple-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">GDPR Wijzigen</h3>
        <p class="text-sm" style="color: var(--text-muted);">Privacy instellingen</p>
    </a>
    
    <a href="/andy/kantoor/klantenbestand.php" class="card-glass p-6 group">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-pink-500/30 to-rose-500/30 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
            <i class="bi bi-people-fill text-2xl text-pink-400"></i>
        </div>
        <h3 class="text-xl font-semibold mb-1">Klantenbestand</h3>
        <p class="text-sm" style="color: var(--text-muted);">Klantgegevens beheren</p>
    </a>
    
</div>

<!-- VERSION INFO -->
<div class="mt-10 text-center" style="color: var(--text-muted);">
    <p class="text-sm">Windels Green & Deco Resin | Versie 0.0.6.3</p>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/admin/includes/footer.php'; ?>
