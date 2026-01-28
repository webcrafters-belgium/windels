<?php
include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';

// 🔐 Beveiliging: enkel ingelogde partner
session_start();
$partnerId=$_SESSION['partner_id']??0;

if(!$partnerId){
    header("Location:/pages/account/login.php");
    exit;
}

// Basisgegevens partner
$sqlPartner="SELECT bedrijf_naam,contact_naam,adres,telefoon,email,logo FROM funeral_partners WHERE id=? LIMIT 1";
$stmtP=$mysqli->prepare($sqlPartner);
$stmtP->bind_param("i",$partnerId);
$stmtP->execute();
$partner=$stmtP->get_result()->fetch_assoc()??[];

// Sociale media velden
$platforms=[
    'facebook_url'=>'Facebook',
    'instagram_url'=>'Instagram',
    'linkedin_url'=>'LinkedIn',
    'website_url'=>'Website',
    'youtube_url'=>'YouTube',
    'twitter_url'=>'Twitter',
    'tiktok_url'=>'TikTok',
    'pinterest_url'=>'Pinterest',
    'whatsapp_url'=>'WhatsApp',
    'telegram_url'=>'Telegram'
];

// Ophalen van bestaande info
$sql="SELECT * FROM funeral_partner_info WHERE partner_id=?";
$stmt=$mysqli->prepare($sql);
$stmt->bind_param("i",$partnerId);
$stmt->execute();
$result=$stmt->get_result();
$data=$result->fetch_assoc()??[];
$success=false;

// mappen
$bannerDir=$_SERVER['DOCUMENT_ROOT'].'/uploads/partners/';
$profileDir=$_SERVER['DOCUMENT_ROOT'].'/uploads/logos/';
if(!is_dir($bannerDir)){@mkdir($bannerDir,0755,true);}
if(!is_dir($profileDir)){@mkdir($profileDir,0755,true);}

function uploadPartnerImage($field,$prefix,$partnerId,$dir){
    if(empty($_FILES[$field]['name'])||$_FILES[$field]['error']!==UPLOAD_ERR_OK){return null;}
    $allowed=['jpg','jpeg','png','webp'];
    $ext=strtolower(pathinfo($_FILES[$field]['name'],PATHINFO_EXTENSION));
    if(!in_array($ext,$allowed)){return null;}
    $filename=$prefix.'_'.$partnerId.'_'.time().'.'.$ext;
    $target=$dir.$filename;
    if(move_uploaded_file($_FILES[$field]['tmp_name'],$target)){
        return $filename;
    }
    return null;
}

if($_SERVER['REQUEST_METHOD']==='POST'){

    // 🗑 Eerst bestaande bestanden verwijderen (op basis van oorspronkelijke $data)
    if(!empty($_POST['delete_banner']) && !empty($data['banner_image'])){
        $file=$bannerDir.$data['banner_image'];
        if(file_exists($file)){unlink($file);}

        $sqlDel="UPDATE funeral_partner_info SET banner_image=NULL WHERE partner_id=?";
        $stmtDel=$mysqli->prepare($sqlDel);
        $stmtDel->bind_param("i",$partnerId);
        $stmtDel->execute();

        $data['banner_image']=null;
        $success=true;
    }

    if(!empty($_POST['delete_profile']) && !empty($data['profile_image'])){
        $file=$profileDir.$data['profile_image'];
        if(file_exists($file)){unlink($file);}

        $sqlDel="UPDATE funeral_partner_info SET profile_image=NULL WHERE partner_id=?";
        $stmtDel=$mysqli->prepare($sqlDel);
        $stmtDel->bind_param("i",$partnerId);
        $stmtDel->execute();

        $data['profile_image']=null;
        $success=true;
    }

    // ✏️ Daarna gewone update / upload
    $over_ons=trim($_POST['over_ons_tekst']??'');

    // kleur valideren
    $theme_color=trim($_POST['theme_color']??'#1e4025');
    if(!preg_match('/^#[0-9A-Fa-f]{6}$/',$theme_color)){
        $theme_color='#1e4025';
    }

    // huidige (eventueel al gewiste) waarden als basis
    $currentBanner=$data['banner_image']??null;
    $currentProfile=$data['profile_image']??null;

    // upload naar juiste map
    $newBanner=uploadPartnerImage('banner_image','banner',$partnerId,$bannerDir);
    $newProfile=uploadPartnerImage('profile_image','profile',$partnerId,$profileDir);

    if($newBanner){$currentBanner=$newBanner;}
    if($newProfile){$currentProfile=$newProfile;}

    $social_values=[];
    foreach($platforms as $field=>$label){
        $social_values[$field]=trim($_POST[$field]??'');
    }

    $social_values['theme_color']=$theme_color;
    $social_values['banner_image']=$currentBanner;
    $social_values['profile_image']=$currentProfile;

    $columns=array_keys($social_values);
    $placeholders=implode(', ',array_map(fn($c)=>"$c = ?",$columns));
    $params=array_merge([$over_ons],array_values($social_values));
    $types=str_repeat('s',count($params));

    if($data){
        $sql="UPDATE funeral_partner_info SET over_ons_tekst=?, $placeholders WHERE partner_id=?";
        $params[]=$partnerId;
        $types.='i';
    }else{
        $sql="INSERT INTO funeral_partner_info (over_ons_tekst,".implode(', ',$columns).",partner_id) VALUES (".str_repeat('?,',count($params))."?)";
        $params[]=$partnerId;
        $types.='i';
    }

    $stmt=$mysqli->prepare($sql);
    $stmt->bind_param($types,...$params);
    $stmt->execute();

    $success=true;

    // data updaten voor live preview
    $data=array_merge($data,[
        'over_ons_tekst'=>$over_ons,
        'theme_color'=>$theme_color,
        'banner_image'=>$currentBanner,
        'profile_image'=>$currentProfile
    ],$social_values);
}

/* ====== PREVIEW VARIABELEN OPBOUWEN ====== */
$themeColor=$data['theme_color']??'#1e4025';

// logo fallback
$logoBestand=$partner['logo']??'';
$logoPad=$logoBestand?$_SERVER['DOCUMENT_ROOT']."/uploads/logos/".$logoBestand:null;
$logoUrl=($logoBestand&&$logoPad&&file_exists($logoPad))?"/uploads/logos/".$logoBestand:"/assets/images/logo-placeholder.png";

// banner
$bannerBestand=$data['banner_image']??'';
if($bannerBestand){
    $bannerPad=$bannerDir.$bannerBestand;
    $bannerUrl=file_exists($bannerPad)?"/uploads/partners/".$bannerBestand:"/img/uitvaartachtergrond.jpg";
}else{
    $bannerUrl="/img/uitvaartachtergrond.jpg";
}

// profielfoto
$profileBestand=$data['profile_image']??'';
if($profileBestand){
    $profilePad=$profileDir.$profileBestand;
    $profileUrl=file_exists($profilePad)?"/uploads/logos/".$profileBestand:$logoUrl;
}else{
    $profileUrl=$logoUrl;
}

$stylePreview="--theme-color:".htmlspecialchars($themeColor).";--banner:url('".$bannerUrl."');";

include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php';
?>


<main class="partner-mij-container">
<h1 class="partner-mij-title">Profielstijl &amp; over mij</h1>
<p class="partner-mij-subtext">Deze informatie wordt publiek getoond op jouw uitvaartpartnerpagina en is zichtbaar via de navigatiebalk voor nabestaanden en uitvaartdiensten.</p>
<div class="back-wrapper">
    <a href="/pages/account/profiel/index.php" class="back-btn">← Terug naar profiel</a>
</div>


    <?php if(!empty($success)):?>
        <div class="alert-success">Je gegevens zijn opgeslagen.</div>
    <?php endif;?>

    <!-- LIVE PREVIEW VAN PUBLIEK PROFIEL -->
    <section class="partner-preview-wrapper">
        <div class="partner-preview-card" style="<?=$stylePreview?>">
            <div class="partner-preview-header">
                <div class="partner-preview-overlay">
                    <div class="partner-preview-avatar">
                        <img src="<?=$profileUrl?>" alt="Profielfoto van <?=htmlspecialchars($partner['bedrijf_naam']??'')?>">
                    </div>
                    <div class="partner-preview-text">
                        <h2><?=htmlspecialchars($partner['bedrijf_naam']??'')?></h2>
                        <?php if(!empty($partner['contact_naam'])):?>
                            <p class="preview-contact"><strong>Contactpersoon:</strong> <?=htmlspecialchars($partner['contact_naam'])?></p>
                        <?php endif;?>
                        <?php if(!empty($partner['adres'])):?>
                            <p class="preview-adres"><?=nl2br(htmlspecialchars($partner['adres']))?></p>
                        <?php endif;?>
                        <?php if(!empty($partner['telefoon'])):?>
                            <p><strong>Telefoon:</strong> <?=htmlspecialchars($partner['telefoon'])?></p>
                        <?php endif;?>
                        <?php if(!empty($partner['email'])):?>
                            <p><strong>E-mail:</strong>
                                <a href="mailto:<?=htmlspecialchars($partner['email'])?>"><?=htmlspecialchars($partner['email'])?></a>
                            </p>
                        <?php endif;?>
                    </div>
                </div>
            </div>

            <?php if(!empty($data['over_ons_tekst'])):?>
                <div class="partner-preview-overons">
                    <h3>Over ons</h3>
                    <p><?=nl2br(htmlspecialchars($data['over_ons_tekst']))?></p>
                </div>
            <?php endif;?>

            <?php
            $heeftSocial = false;
            foreach($platforms as $field=>$label){
                if(!empty($data[$field])){$heeftSocial=true;break;}
            }
            ?>
            <?php if($heeftSocial):?>
                <div class="partner-preview-social">
                    <h3>Volg ons</h3>
                    <div class="social-icons">
                        <?php if(!empty($data['facebook_url'])):?>
                            <a href="<?=htmlspecialchars($data['facebook_url'])?>" target="_blank" class="facebook" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <?php endif;?>
                        <?php if(!empty($data['instagram_url'])):?>
                            <a href="<?=htmlspecialchars($data['instagram_url'])?>" target="_blank" class="instagram" title="Instagram"><i class="fab fa-instagram"></i></a>
                        <?php endif;?>
                        <?php if(!empty($data['twitter_url'])):?>
                            <a href="<?=htmlspecialchars($data['twitter_url'])?>" target="_blank" class="twitter" title="X (Twitter)"><i class="fab fa-x-twitter"></i></a>
                        <?php endif;?>
                        <?php if(!empty($data['tiktok_url'])):?>
                            <a href="<?=htmlspecialchars($data['tiktok_url'])?>" target="_blank" class="tiktok" title="TikTok"><i class="fab fa-tiktok"></i></a>
                        <?php endif;?>
                        <?php if(!empty($data['youtube_url'])):?>
                            <a href="<?=htmlspecialchars($data['youtube_url'])?>" target="_blank" class="youtube" title="YouTube"><i class="fab fa-youtube"></i></a>
                        <?php endif;?>
                        <?php if(!empty($data['pinterest_url'])):?>
                            <a href="<?=htmlspecialchars($data['pinterest_url'])?>" target="_blank" class="pinterest" title="Pinterest"><i class="fab fa-pinterest-p"></i></a>
                        <?php endif;?>
                        <?php if(!empty($data['whatsapp_url'])):?>
                            <a href="<?=htmlspecialchars($data['whatsapp_url'])?>" target="_blank" class="whatsapp" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                        <?php endif;?>
                        <?php if(!empty($data['telegram_url'])):?>
                            <a href="<?=htmlspecialchars($data['telegram_url'])?>" target="_blank" class="telegram" title="Telegram"><i class="fab fa-telegram"></i></a>
                        <?php endif;?>
                        <?php if(!empty($data['linkedin_url'])):?>
                            <a href="<?=htmlspecialchars($data['linkedin_url'])?>" target="_blank" class="linkedin" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                        <?php endif;?>
                        <?php if(!empty($data['website_url'])):?>
                            <a href="<?=htmlspecialchars($data['website_url'])?>" target="_blank" class="website" title="Website"><i class="fas fa-globe"></i></a>
                        <?php endif;?>
                    </div>
                </div>
            <?php endif;?>
        </div>
    </section>

    <h2 class="partner-mij-subtitle">Instellingen aanpassen</h2>

    <form method="post" enctype="multipart/form-data" class="partner-mij-form">
        <div class="card">
            <h3 class="card-title">Profielstijl</h3>

            <div class="theme-row">
                <div class="theme-field">
                    <label for="theme_color">Hoofdkleur profiel</label>
                    <input type="color" id="theme_color" name="theme_color" value="<?=htmlspecialchars($themeColor)?>">
                    <span class="theme-hex" id="theme_hex"><?=htmlspecialchars($themeColor)?></span>
                </div>
            </div>

            <div class="upload-group">
                <div class="upload-item">
                    <label for="banner_image">Bannerafbeelding</label>
                    <input type="file" name="banner_image" id="banner_image" accept="image/*">
                    <?php if(!empty($data['banner_image'])):?>
                        <div class="upload-preview">
                            <img src="/uploads/partners/<?=htmlspecialchars($data['banner_image'])?>" alt="Huidige banner">
                            <button type="submit" name="delete_banner" value="1" class="btn-remove-small">Banner verwijderen</button>
                        </div>
                    <?php endif;?>
                </div>

                <div class="upload-item">
                    <label for="profile_image">Profielfoto</label>
                    <input type="file" name="profile_image" id="profile_image" accept="image/*">
                    <?php if(!empty($data['profile_image'])):?>
                        <div class="upload-preview">
                            <img src="/uploads/logos/<?=htmlspecialchars($data['profile_image'])?>" alt="Huidige profielfoto">
                            <button type="submit" name="delete_profile" value="1" class="btn-remove-small">Profielfoto verwijderen</button>
                        </div>
                    <?php endif;?>
                </div>
            </div>
        </div>

        <div class="card">
            <h3 class="card-title">Over mij</h3>
            <textarea name="over_ons_tekst" rows="6"><?=htmlspecialchars($data['over_ons_tekst']??'')?></textarea>
        </div>

        <div class="card">
            <h3 class="card-title">Sociale media</h3>
            <div id="social-container"></div>
            <button type="button" onclick="addSocialInput()" class="btn-green mt-3">+ Nog een platform toevoegen</button>
        </div>

        <button type="submit" class="btn-green">Opslaan</button>
    </form>

</main>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<style>
body{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;background-size:cover;}
.partner-mij-container{width:100%;max-width:950px;margin:2rem auto;padding:3rem 1rem;background-color:rgba(255,255,255,.92);border-radius:24px;box-shadow:0 4px 10px rgba(0,0,0,.05);}
.partner-mij-title{text-align:center;font-size:2rem;font-weight:700;color:#1e4025;margin-bottom:1.5rem;}
.partner-mij-subtext{
    text-align:center;
    margin-top:-1rem;
    margin-bottom:1.5rem;
    font-size:1rem;
    color:#1e4025;
    opacity:0.85;
}
.back-wrapper{
    text-align:center;
    margin-bottom:1.5rem;
}

.back-btn{
    display:inline-block;
    background-color:#1e4025;
    color:#fff;
    padding:0.55rem 1.3rem;
    border-radius:30px;
    text-decoration:none;
    font-weight:600;
    transition:background-color .2s ease;
}

.back-btn:hover{
    background-color:#2e6a3f;
}

.partner-mij-subtitle{margin-top:2.5rem;font-size:1.3rem;font-weight:600;color:#1e4025;margin-bottom:1rem;}
.alert-success{background-color:#e0f4e5;padding:1rem;border-radius:10px;border:1px solid #bde5ce;color:#155724;margin-bottom:1.2rem;}
.partner-mij-form{display:flex;flex-direction:column;gap:2rem;}
.card{background:#fff;border-radius:18px;padding:1.8rem;box-shadow:0 2px 6px rgba(0,0,0,.06);}
.card-title{font-size:1.1rem;font-weight:600;color:#1e4025;margin-bottom:1rem;}
textarea,input[type="url"],select{border:1px solid #ccc;border-radius:10px;padding:10px;width:100%;font-size:1rem;}
.btn-green{background-color:#1e4025;color:#fff;padding:.6rem 1.4rem;border-radius:30px;border:none;font-weight:600;cursor:pointer;}
.btn-green:hover{background-color:#2e6a3f;}
.btn-remove{background-color:#8b1c1c;color:#fff;padding:.5rem 1.2rem;border:none;border-radius:30px;font-weight:500;cursor:pointer;transition:background-color .2s;}
.btn-remove:hover{background-color:#a62626;}
#social-container>div{display:flex;flex-wrap:wrap;align-items:center;gap:12px;background:#f9f9f9;padding:1rem;margin-bottom:1rem;border:1px solid #ddd;border-radius:12px;}
.theme-row{display:flex;flex-wrap:wrap;gap:1.5rem;align-items:center;margin-bottom:1.5rem;}
.theme-field{display:flex;align-items:center;gap:.75rem;}
.theme-field label{font-weight:500;}
.theme-field input[type="color"]{width:40px;height:40px;padding:0;border-radius:8px;border:1px solid #ccc;cursor:pointer;}
.theme-hex{font-family:monospace;font-size:.9rem;color:#555;}
.upload-group{display:flex;flex-wrap:wrap;gap:1.5rem;}
.upload-item{flex:1 1 260px;}
.upload-item label{display:block;font-weight:500;margin-bottom:.4rem;}
.upload-preview{margin-top:.5rem;}
.upload-preview img{max-width:100%;max-height:120px;border-radius:10px;object-fit:cover;border:1px solid #ddd;}

/* PREVIEW CARD STYLING */
.partner-preview-wrapper{margin-bottom:1.5rem;}
.partner-preview-card{background:#f7f7f7;border-radius:18px;box-shadow:0 4px 12px rgba(0,0,0,.08);overflow:hidden;--theme-color:#1e4025;}
.partner-preview-header{position:relative;height:200px;background-image:linear-gradient(120deg,rgba(0,0,0,.45),rgba(0,0,0,.25)),var(--banner);background-size:cover;background-position:center;}
.partner-preview-overlay{position:absolute;inset:0;padding:1.5rem 1.8rem;display:flex;align-items:flex-end;gap:1rem;}
.partner-preview-avatar img{width:90px;height:90px;border-radius:50%;object-fit:cover;background:#fff;padding:4px;box-shadow:0 4px 10px rgba(0,0,0,.25);border:3px solid rgba(255,255,255,.7);}
.partner-preview-text{color:#fff;}
.partner-preview-text h2{font-size:1.6rem;margin:0 0 .4rem 0; color:#fff}
.preview-contact{margin:.2rem 0 .3rem 0;font-weight:500;}
.preview-adres{margin:0 0 .3rem 0;font-size:.95rem;}
.partner-preview-text a{color:#fff;text-decoration:underline;font-weight:500;}
.partner-preview-text a:hover{text-decoration:none;opacity:.9;}
.partner-preview-overons,.partner-preview-social{padding:1.4rem 1.8rem 1rem 1.8rem;}
.partner-preview-overons h3,.partner-preview-social h3{font-size:1.1rem;margin-bottom:.4rem;color:#222;}
.partner-preview-overons p{margin:0 0 .6rem 0;color:#444;line-height:1.6;}
.social-icons{display:flex;flex-wrap:wrap;gap:10px;margin-top:.5rem;}
.social-icons a{display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;color:#fff;font-size:1.05rem;background-color:#4a4a4a;text-decoration:none;transition:background-color .3s,transform .2s;}
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
.btn-remove-small{
    background-color:#8b1c1c;
    color:#fff;
    padding:0.4rem 1rem;
    border:none;
    border-radius:20px;
    cursor:pointer;
    font-size:0.85rem;
    margin-top:0.6rem;
    display:inline-block;
}
.btn-remove-small:hover{
    background-color:#a62626;
}

@media(max-width:768px){
    .partner-mij-container{padding:2rem 1rem;}
    .partner-mij-title{font-size:1.6rem;}
    .partner-preview-header{height:230px;}
    .partner-preview-overlay{flex-direction:column;align-items:flex-start;justify-content:flex-end;padding:1.4rem;}
    .partner-preview-avatar img{width:80px;height:80px;}
    .partner-preview-text h2{font-size:1.4rem;}
    .partner-preview-overons,.partner-preview-social{padding:1.2rem 1.3rem 1rem 1.3rem;}
    .partner-mij-subtitle{margin-top:2rem;}
    #social-container>div{flex-direction:column;align-items:flex-start;}
}
</style>

<script>
const availablePlatforms=<?=json_encode($platforms)?>;

function addSocialInput(selectedKey='',url=''){
    const container=document.getElementById('social-container');
    const div=document.createElement('div');
    let options='';
    for(const key in availablePlatforms){
        const selected=key===selectedKey?'selected':'';
        options+=`<option value="${key}" ${selected}>${availablePlatforms[key]}</option>`;
    }
    const currentName=selectedKey||'facebook_url';
    div.innerHTML=`
        <select onchange="this.nextElementSibling.name=this.value">${options}</select>
        <input type="url" name="${currentName}" value="${url}" placeholder="https://..." class="p-2 border rounded w-full max-w-[300px]">
        <button type="button" onclick="this.parentElement.remove()" class="btn-remove">Verwijder</button>
    `;
    container.appendChild(div);
}

// bestaande social links laden
<?php foreach($platforms as $field=>$label):?>
<?php if(!empty($data[$field])):?>
addSocialInput("<?= $field?>","<?= htmlspecialchars($data[$field],ENT_QUOTES)?>");
<?php endif;?>
<?php endforeach;?>

// kleur live tonen in preview + hex
document.addEventListener('DOMContentLoaded',function(){
    const colorInput=document.getElementById('theme_color');
    const hexSpan=document.getElementById('theme_hex');
    const preview=document.querySelector('.partner-preview-card');
    if(colorInput && preview){
        colorInput.addEventListener('input',function(){
            preview.style.setProperty('--theme-color',this.value);
            if(hexSpan){hexSpan.textContent=this.value;}
        });
    }
});
</script>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php';?>
