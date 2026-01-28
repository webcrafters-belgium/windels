<?php
function getActiveVacationBanner($conn): void
{
    $today = date('Y-m-d');

    // Haal eerst de eerstvolgende of huidige vakantieperiode op
    $sql = "SELECT * FROM vacation_periods 
            WHERE end_date >= ? 
            ORDER BY start_date ASC 
            LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $today);
    $stmt->execute();
    $result = $stmt->get_result();

    $vacation = $result->fetch_assoc();
    if (!$vacation) return;

    $start = new DateTime($vacation['start_date']);
    $end   = new DateTime($vacation['end_date']);
    $now   = new DateTime($today);

    // Speciale uitzondering
    $special_start = new DateTime('2025-06-29');
    $special_end   = new DateTime('2025-07-06');

    if ($now >= $special_start && $now <= $special_end) {
        include $_SERVER['DOCUMENT_ROOT'] . '/partials/home/special_discount_banner.php';
        return;
    }

    // Tijdens de vakantie
    if ($now >= $start && $now <= $end) {
        include $_SERVER['DOCUMENT_ROOT'] . '/partials/home/vacation_period_banner.php';
        return;
    }

    // Binnen 14 dagen start vakantie → toon "coming soon"
    $interval = $now->diff($start)->days;
    if ($now < $start && $interval <= 21) {
        include $_SERVER['DOCUMENT_ROOT'] . '/partials/home/vacation_coming_soon_banner.php';
    }
}

