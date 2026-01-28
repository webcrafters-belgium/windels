<?php
include dirname($_SERVER['DOCUMENT_ROOT']) . '/secure/ini.inc';
include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/header.php';

$id = intval($_GET['id'] ?? 0);
$partner = null;

if ($id > 0) {
    $sql = "
        SELECT p.id, p.bedrijf_naam, p.contact_naam, p.adres, p.telefoon, p.email, p.logo,
               i.over_ons_tekst, i.facebook_url, i.instagram_url, i.linkedin_url, i.website_url
        FROM funeral_partners p
        LEFT JOIN funeral_partner_info i ON p.id = i.partner_id
        WHERE p.id = $id
        LIMIT 1
    ";
    $res = $mysqli->query($sql);
    $partner = $res->fetch_assoc();
}
?>

<main class="partner-detail">
    <div class="container">
        <?php if (!$partner): ?>
            <p>Uitvaartpartner niet gevonden.</p>
        <?php else: ?>
            <div class="partner-card">
                <div class="partner-header">
                    <?php
                    $logoBestand = $partner['logo'] ?? '';
                    $logoVolledigPad = $_SERVER['DOCUMENT_ROOT'] . "/uploads/logos/" . $logoBestand;
                    $logoPad = ($logoBestand && file_exists($logoVolledigPad))
                        ? "/uploads/logos/" . $logoBestand
                        : "/assets/images/logo-placeholder.png";
                    
                    ?>

                    <div class="partner-logo">
                        <img src="<?= $logoPad ?>" alt="Logo van <?= htmlspecialchars($partner['bedrijf_naam']) ?>">
                    </div>

                    <h1><?= htmlspecialchars($partner['bedrijf_naam']) ?></h1>
                    
                    <p><strong>Contactpersoon:</strong><br><?= htmlspecialchars($partner['contact_naam']) ?></p>
                    <p><strong>Adres:</strong><br><?= nl2br(htmlspecialchars($partner['adres'])) ?></p>
                    
                    <?php if ($partner['telefoon']): ?>
                        <p><strong>Telefoon:</strong><br><?= htmlspecialchars($partner['telefoon']) ?></p>
                    <?php endif; ?>

                    <p><strong>E-mail:</strong><br>
                        <a href="mailto:<?= htmlspecialchars($partner['email']) ?>">
                            <?= htmlspecialchars($partner['email']) ?>
                        </a>
                    </p>
                </div>



                <?php if (!empty($partner['over_ons_tekst'])): ?>
                    <div class="partner-overons">
                        <h2>Over ons</h2>
                        <p><?= nl2br(htmlspecialchars($partner['over_ons_tekst'])) ?></p>
                    </div>
                <?php endif; ?>

                <?php if ($partner['facebook_url'] || $partner['instagram_url'] || $partner['linkedin_url'] || $partner['website_url']): ?>
                    <div class="partner-social">
                        <h2>Volg ons</h2>
                        <div class="social-icons">
                            <?php if ($partner['facebook_url']): ?>
                                <a href="<?= htmlspecialchars($partner['facebook_url']) ?>" target="_blank" class="facebook" title="Facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($partner['instagram_url']): ?>
                                <a href="<?= htmlspecialchars($partner['instagram_url']) ?>" target="_blank" class="instagram" title="Instagram">
                                    <i class="fab fa-instagram"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($partner['linkedin_url']): ?>
                                <a href="<?= htmlspecialchars($partner['linkedin_url']) ?>" target="_blank" class="linkedin" title="LinkedIn">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            <?php endif; ?>
                            <?php if ($partner['website_url']): ?>
                                <a href="<?= htmlspecialchars($partner['website_url']) ?>" target="_blank" class="website" title="Website">
                                    <i class="fas fa-globe"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="terug-link">
                    <a href="contacteer-uitvaartdienst.php" class="btn">← Terug naar overzicht</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>
body{
    background: url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;
    background-size: cover;
}
.partner-detail {
    padding: 3rem 0;
}
.partner-card {
    background: #f7f7f7;
    padding: 2rem;
    border-radius: 18px;
    max-width: 800px;
    margin: auto;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
}
.partner-header {
    text-align: center;
    margin-bottom: 2rem;
}

.partner-header h1 {
    margin-top: 1rem;
    font-size: 1.8rem;
    color: #222;
}

.partner-header p {
    margin: 0.5rem 0;
    font-size: 1rem;
    color: #444;
}

.partner-logo {
    margin: 0 auto 1.5rem;
}

.partner-logo img {
    width: 130px;
    height: 130px;
    object-fit: cover;
    border-radius: 50%;
    background-color: #fff;
    padding: 10px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    border: 3px solid #e4e4e4;
}

.partner-overons, .partner-social {
    margin-top: 2rem;
}
.social-icons {
    display: flex;
    gap: 12px;
    margin-top: 0.5rem;
}
.social-icons a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    color: #fff;
    font-size: 1.1rem;
    background-color: #4a4a4a;
    text-decoration: none;
    transition: background-color 0.3s ease, transform 0.2s ease;
}
.social-icons a:hover {
    transform: scale(1.1);
}
.social-icons a.facebook { background-color: #3b5998; color: #fff;}
.social-icons a.instagram { background: radial-gradient(circle at 30% 30%, #fdf497 0%, #fdf497 5%, #fd5949 45%, #d6249f 60%, #285AEB 90%); color: #fff;}
.social-icons a.linkedin { background-color: #0077b5; color: #fff;}
.social-icons a.website  { background-color: #1e4025; color: #fff;}
.social-icons a.youtube    { background-color: #ff0000;  color: #fff;}
.social-icons a.twitter    { background-color: #1da1f2;  color: #fff;}
.social-icons a.tiktok     { background-color: #000000;  color: #fff;}
.social-icons a.pinterest  { background-color: #bd081c;  color: #fff;}
.social-icons a.whatsapp   { background-color: #25D366;  color: #fff;}
.social-icons a.telegram   { background-color: #0088cc;  color: #fff;}
.terug-link {
    margin-top: 2rem;
    text-align: left;
}
.terug-link .btn {
    background-color: #1e4025;
    color: #fff;
    padding: 0.6rem 1.4rem;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
}
.terug-link .btn:hover {
    background-color: #2e6a3f;
}
.partner-header a,
.partner-overons a,
.partner-social a {
    color: #1e4025;
    text-decoration: underline;
    font-weight: 500;
    transition: color 0.2s ease;
}

.partner-header a:hover,
.partner-overons a:hover,
.partner-social a:hover {
    color: #2e6a3f;
    text-decoration: none;
}

</style>

<?php include dirname($_SERVER['DOCUMENT_ROOT']) . '/partials/footer.php'; ?>
