<?php
include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';

$id=intval($_GET['id']??0);
$partner=null;

if($id>0){
    $sql="
        SELECT 
            p.id,p.bedrijf_naam,p.contact_naam,p.adres,p.telefoon,p.email,p.logo,
            i.over_ons_tekst,i.facebook_url,i.instagram_url,i.linkedin_url,i.twitter_url,
            i.youtube_url,i.pinterest_url,i.whatsapp_url,i.telegram_url,i.tiktok_url,i.website_url,
            i.theme_color,i.banner_image,i.profile_image
        FROM funeral_partners p
        LEFT JOIN funeral_partner_info i ON p.id=i.partner_id
        WHERE p.id=? LIMIT 1
    ";
    $stmt=$mysqli->prepare($sql);
    $stmt->bind_param("i",$id);
    $stmt->execute();
    $res=$stmt->get_result();
    $partner=$res->fetch_assoc();
}

?>
<main class="partner-detail">
    <div class="container">
        <?php if(!$partner):?>
            <p>Uitvaartpartner niet gevonden.</p>
        <?php else:?>
            <?php
            $logoBestand=$partner['logo']??'';
            $logoVolledigPad=$_SERVER['DOCUMENT_ROOT']."/uploads/logos/".$logoBestand;
            $logoPad=($logoBestand&&file_exists($logoVolledigPad))?"/uploads/logos/".$logoBestand:"/assets/images/logo-placeholder.png";

            $bannerBestand=$partner['banner_image']??'';
            $bannerVolledigPad=$bannerBestand?$_SERVER['DOCUMENT_ROOT']."/uploads/partners/".$bannerBestand:null;
            $bannerUrl=($bannerBestand&&$bannerVolledigPad&&file_exists($bannerVolledigPad))?"/uploads/partners/".$bannerBestand:"/img/uitvaartachtergrond.jpg";

            $profileBestand=$partner['profile_image']??'';
            $profileVolledigPad=$profileBestand?$_SERVER['DOCUMENT_ROOT']."/uploads/partners/".$profileBestand:null;
            $profileUrl=($profileBestand&&$profileVolledigPad&&file_exists($profileVolledigPad))?"/uploads/partners/".$profileBestand:$logoPad;

            $themeColor=$partner['theme_color']?:'#1e4025';
            $style="--theme-color:".htmlspecialchars($themeColor).";--banner:url('".$bannerUrl."');";
            ?>
            <div class="partner-card" style="<?=$style?>">
                <div class="partner-header">
                    <div class="partner-header-overlay">
                        <div class="partner-avatar">
                            <img src="<?=$profileUrl?>" alt="Profielfoto van <?=htmlspecialchars($partner['bedrijf_naam']??'')?>">
                        </div>
                        <div class="partner-header-text">
                            <h1><?=htmlspecialchars($partner['bedrijf_naam']??'')?></h1>
                            <?php if(!empty($partner['contact_naam'])):?>
                                <p class="partner-contact"><strong>Contactpersoon:</strong> <?=htmlspecialchars($partner['contact_naam'])?></p>
                            <?php endif;?>
                            <?php if(!empty($partner['adres'])):?>
                                <p class="partner-adres"><?=nl2br(htmlspecialchars($partner['adres']))?></p>
                            <?php endif;?>
                            <?php if(!empty($partner['telefoon'])):?>
                                <p><strong>Telefoon:</strong> <?=htmlspecialchars($partner['telefoon'])?></p>
                            <?php endif;?>
                            <p><strong>E-mail:</strong>
                                <a href="mailto:<?=htmlspecialchars($partner['email'])?>"><?=htmlspecialchars($partner['email']??'')?></a>
                            </p>
                        </div>
                    </div>
                </div>

                <?php if(!empty($partner['over_ons_tekst'])):?>
                    <div class="partner-overons">
                        <h2>Over ons</h2>
                        <p><?=nl2br(htmlspecialchars($partner['over_ons_tekst']))?></p>
                    </div>
                <?php endif;?>

                <?php if(
                    $partner['facebook_url']||$partner['instagram_url']||$partner['linkedin_url']||
                    $partner['website_url']||$partner['twitter_url']||$partner['whatsapp_url']||
                    $partner['telegram_url']||$partner['pinterest_url']||$partner['tiktok_url']||
                    $partner['youtube_url']
                ):?>
                    <div class="partner-social">
                        <h2>Volg ons</h2>
                        <div class="social-icons">
                            <?php if($partner['facebook_url']):?>
                                <a href="<?=htmlspecialchars($partner['facebook_url'])?>" target="_blank" class="facebook" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                            <?php endif;?>
                            <?php if($partner['instagram_url']):?>
                                <a href="<?=htmlspecialchars($partner['instagram_url'])?>" target="_blank" class="instagram" title="Instagram"><i class="fab fa-instagram"></i></a>
                            <?php endif;?>
                            <?php if($partner['twitter_url']):?>
                                <a href="<?=htmlspecialchars($partner['twitter_url'])?>" target="_blank" class="twitter" title="X (Twitter)"><i class="fab fa-x-twitter"></i></a>
                            <?php endif;?>
                            <?php if($partner['tiktok_url']):?>
                                <a href="<?=htmlspecialchars($partner['tiktok_url'])?>" target="_blank" class="tiktok" title="TikTok"><i class="fab fa-tiktok"></i></a>
                            <?php endif;?>
                            <?php if($partner['youtube_url']):?>
                                <a href="<?=htmlspecialchars($partner['youtube_url'])?>" target="_blank" class="youtube" title="YouTube"><i class="fab fa-youtube"></i></a>
                            <?php endif;?>
                            <?php if($partner['pinterest_url']):?>
                                <a href="<?=htmlspecialchars($partner['pinterest_url'])?>" target="_blank" class="pinterest" title="Pinterest"><i class="fab fa-pinterest-p"></i></a>
                            <?php endif;?>
                            <?php if($partner['whatsapp_url']):?>
                                <a href="<?=htmlspecialchars($partner['whatsapp_url'])?>" target="_blank" class="whatsapp" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                            <?php endif;?>
                            <?php if($partner['telegram_url']):?>
                                <a href="<?=htmlspecialchars($partner['telegram_url'])?>" target="_blank" class="telegram" title="Telegram"><i class="fab fa-telegram"></i></a>
                            <?php endif;?>
                            <?php if($partner['linkedin_url']):?>
                                <a href="<?=htmlspecialchars($partner['linkedin_url'])?>" target="_blank" class="linkedin" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                            <?php endif;?>
                            <?php if($partner['website_url']):?>
                                <a href="<?=htmlspecialchars($partner['website_url'])?>" target="_blank" class="website" title="Website"><i class="fas fa-globe"></i></a>
                            <?php endif;?>
                        </div>
                    </div>
                <?php endif;?>

                <div class="terug-link">
                    <a href="contacteer-uitvaartdienst.php" class="btn">← Terug naar overzicht</a>
                </div>
            </div>
        <?php endif;?>
    </div>
</main>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>
body{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;background-size:cover;}
.partner-detail{padding:3rem 0;}
.partner-card{background:#f7f7f7;border-radius:18px;max-width:900px;margin:auto;box-shadow:0 4px 12px rgba(0,0,0,.08);overflow:hidden;--theme-color:#1e4025;}
.partner-header{position:relative;height:220px;background-image:linear-gradient(120deg,rgba(0,0,0,.45),rgba(0,0,0,.25)),var(--banner);background-size:cover;background-position:center;}
.partner-header-overlay{position:absolute;inset:0;padding:1.8rem 2rem;display:flex;align-items:flex-end;gap:1.2rem;}
.partner-avatar{flex-shrink:0;}
.partner-avatar img{width:120px;height:120px;border-radius:50%;object-fit:cover;background:#fff;padding:4px;box-shadow:0 4px 10px rgba(0,0,0,.25);border:3px solid rgba(255,255,255,.7);}
.partner-header-text{color:#fff;}
.partner-header-text h1{font-size:1.9rem;margin:0 0 .4rem 0;}
.partner-contact{margin:.2rem 0 .4rem 0;font-weight:500;}
.partner-adres{margin:0 0 .3rem 0;font-size:.95rem;}
.partner-header-text a{color:#fff;text-decoration:underline;font-weight:500;}
.partner-header-text a:hover{text-decoration:none;opacity:.9;}
.partner-overons,.partner-social{padding:1.8rem 2rem 0 2rem;}
.partner-overons h2,.partner-social h2{font-size:1.2rem;margin-bottom:.6rem;color:#222;}
.partner-overons p{margin:0 0 1.2rem 0;color:#444;line-height:1.6;}
.social-icons{display:flex;flex-wrap:wrap;gap:10px;margin-top:.5rem;}
.social-icons a{display:inline-flex;align-items:center;justify-content:center;width:40px;height:40px;border-radius:50%;color:#fff;font-size:1.05rem;background-color:#4a4a4a;text-decoration:none;transition:background-color .3s,transform .2s;}
.social-icons a:hover{transform:scale(1.08);text-decoration:none;}
.social-icons a.facebook{background-color:#3b5998;}
.social-icons a.instagram{background:radial-gradient(circle at 30% 30%,#fdf497 0,#fdf497 5%,#fd5949 45%,#d6249f 60%,#285AEB 90%);}
.social-icons a.linkedin{background-color:#0077b5;}
.social-icons a.website{background-color:var(--theme-color);}
.social-icons a.youtube{background-color:#ff0000;}
.social-icons a.twitter{background-color:#000;}
.social-icons a.tiktok{background-color:#000;}
.social-icons a.pinterest{background-color:#bd081c;}
.social-icons a.whatsapp{background-color:#25D366;}
.social-icons a.telegram{background-color:#0088cc;}
.terug-link{padding:1.8rem 2rem 2rem 2rem;}
.terug-link .btn{background-color:var(--theme-color);color:#fff;padding:.6rem 1.4rem;border-radius:30px;text-decoration:none;font-weight:600;border:none;display:inline-block;}
.terug-link .btn:hover{background-color:#2e6a3f;}
@media(max-width:768px){
    .partner-detail{padding:2rem 0;}
    .partner-header{height:260px;}
    .partner-header-overlay{flex-direction:column;align-items:flex-start;justify-content:flex-end;padding:1.5rem;}
    .partner-avatar img{width:100px;height:100px;}
    .partner-header-text h1{font-size:1.6rem;}
    .partner-overons,.partner-social,.terug-link{padding:1.4rem 1.4rem 0 1.4rem;}
}
</style>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php';?>
