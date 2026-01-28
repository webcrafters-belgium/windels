<?php
// ==== Sessies + ini ====

include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
session_start();
// ==== CSRF + methode ====
if($_SERVER['REQUEST_METHOD']!=='POST'){ header('Location: /'); exit; }
if(empty($_POST['csrf'])||$_POST['csrf']!==($_SESSION['csrf']??'')){
  http_response_code(400); die('Ongeldige aanvraag (CSRF).');
}

$IS_PARTNER = !empty($_SESSION['partner_id']);
$partner_id = $IS_PARTNER ? (int)$_SESSION['partner_id'] : 0;

// ==== DB-handle (voor partners) ====
$DB=null;
if(isset($mysqli_medewerkers)&&$mysqli_medewerkers instanceof mysqli){ $DB=$mysqli_medewerkers; }
elseif(isset($mysqli)&&$mysqli instanceof mysqli){ $DB=$mysqli; }
if($IS_PARTNER && !$DB){ http_response_code(500); die('DB-verbinding ontbreekt (mysqli).'); }

// ==== URLs ====
$CART_URL_PUBLIC  = '/pages/orders/cart.php';
$CART_URL_PARTNER = '/pages/account/orders/cart.php';
$CART_URL         = $IS_PARTNER ? $CART_URL_PARTNER : $CART_URL_PUBLIC;

$action = $_POST['action'] ?? '';

// ===== Helpers =====
function back(string $url){ header('Location: '.$url); exit; }

function table_has_column(mysqli $db,string $table,string $column): bool{
  $table=$db->real_escape_string($table); $column=$db->real_escape_string($column);
  $q=$db->query("SHOW COLUMNS FROM `$table` LIKE '$column'"); if($q){ $ok=(bool)$q->num_rows; $q->close(); return $ok; }
  return false;
}

function get_or_create_cart_id(mysqli $db,int $partner_id): int{
  $cid=0;
  if($st=$db->prepare("SELECT id FROM carts WHERE partner_id=?")){
    $st->bind_param('i',$partner_id); $st->execute(); $st->bind_result($cid); $st->fetch(); $st->close();
  }
  if(!$cid && ($st=$db->prepare("INSERT INTO carts(partner_id) VALUES(?)"))){
    $st->bind_param('i',$partner_id); $st->execute(); $cid=$st->insert_id; $st->close();
  }
  return (int)$cid;
}

// Sessiewinkelmand initialiseren (voor particulieren)
if(!$IS_PARTNER){
  if(!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])){
    $_SESSION['cart']=['items'=>[],'currency'=>'<sup>€</sup>'];
  }
  if(empty($_SESSION['cart']['items'])||!is_array($_SESSION['cart']['items'])){
    $_SESSION['cart']['items']=[];
  }
  if(empty($_SESSION['cart']['currency'])) $_SESSION['cart']['currency']='<sup>€</sup>';
}

// ====== ADD ======
if($action==='add'){
  // Normaliseer + whitelist
  $map=['epoxy'=>'epoxy','kaars'=>'kaars','inkoop'=>'inkoop'];
  $ptype = $map[$_POST['product_type'] ?? ''] ?? '';
  $pid   = (int)($_POST['product_id'] ?? 0);
  $name  = trim((string)($_POST['name'] ?? ''));
  $price = (float)($_POST['unit_price'] ?? 0);
  $qty   = max(1,(int)($_POST['qty'] ?? 1));
  if(!$ptype || $pid<=0 || $price<=0 || $name===''){ back($CART_URL); }

  // Variants
  $variant_color=null;
  if(isset($_POST['variant'])&&is_array($_POST['variant'])){
    $variant_color=isset($_POST['variant']['color'])?trim((string)$_POST['variant']['color']):null;
    if($variant_color==='') $variant_color=null;
  }
  $variant_options=[];
  if(!empty($_POST['variant_options'])&&is_array($_POST['variant_options'])){
    foreach($_POST['variant_options'] as $opt){
      $v=trim((string)$opt); if($v!=='') $variant_options[]=$v;
    }
    $variant_options=array_values(array_unique($variant_options));
  }
  $meta=[];
  if($variant_color!==null){ $meta['color']=$variant_color; }
  if(!empty($variant_options)){ $meta['options']=$variant_options; }
  $variant_meta_json = !empty($meta)?json_encode($meta,JSON_UNESCAPED_UNICODE):null;

  // Deterministische variant_key zoals bij partners (handig voor UI)
  $norm=['ptype'=>$ptype,'pid'=>$pid];
  if($variant_color!==null){ $norm['color']=$variant_color; }
  if(!empty($variant_options)){ sort($variant_options,SORT_NATURAL|SORT_FLAG_CASE); $norm['options']=$variant_options; }
  $variant_key = hash('sha256', json_encode($norm,JSON_UNESCAPED_UNICODE));

  if($IS_PARTNER){
    // ===== PARTNER: DB pad blijft identiek =====
    $cart_id = get_or_create_cart_id($DB,$partner_id);
    $HAS_VARIANT_META = table_has_column($DB,'cart_items','variant_meta');
    $HAS_VARIANT_KEY  = table_has_column($DB,'cart_items','variant_key');

    // Optionele prettynaam als variant_meta kolom ontbreekt
    $insert_name=$name;
    if(!$HAS_VARIANT_META){
      $pretty=[];
      if($variant_color){ $pretty[]='kleur: '.$variant_color; }
      if($variant_options){ $pretty[]='opties: '.implode(', ',$variant_options); }
      if($pretty){ $insert_name.=' ('.implode(' | ',$pretty).')'; }
    }

    if($HAS_VARIANT_META && $HAS_VARIANT_KEY){
      $sql="INSERT INTO cart_items (cart_id,product_type,product_id,name,unit_price,qty,variant_meta,variant_key,created_at,updated_at)
            VALUES (?,?,?,?,?,?,?,?,NOW(),NOW())";
      if($st=$DB->prepare($sql)){
        $st->bind_param('isisdiss',$cart_id,$ptype,$pid,$insert_name,$price,$qty,$variant_meta_json,$variant_key);
        $st->execute(); $st->close();
      }
    }elseif($HAS_VARIANT_META && !$HAS_VARIANT_KEY){
      $sql="INSERT INTO cart_items (cart_id,product_type,product_id,name,unit_price,qty,variant_meta,created_at,updated_at)
            VALUES (?,?,?,?,?,?,?,NOW(),NOW())";
      if($st=$DB->prepare($sql)){
        $st->bind_param('isisdis',$cart_id,$ptype,$pid,$insert_name,$price,$qty,$variant_meta_json);
        $st->execute(); $st->close();
      }
    }else{
      $sql="INSERT INTO cart_items (cart_id,product_type,product_id,name,unit_price,qty,created_at,updated_at)
            VALUES (?,?,?,?,?, ?,NOW(),NOW())";
      if($st=$DB->prepare($sql)){
        $st->bind_param('isisdi',$cart_id,$ptype,$pid,$insert_name,$price,$qty);
        $st->execute(); $st->close();
      }
    }

  }else{
    // ===== PARTICULIER: sessiecart =====
    // NB: we houden keys compatibel met je bestaande weergave (fetch_mini_cart_items public path)
    $item=[
      'product_type'=>$ptype,
      'product_id'  =>$pid,
      'title'       =>$name,              // naam voor UI
      'name'        =>$name,              // fallback
      'price'       =>$price,             // unit price
      'qty'         =>$qty,
      'variant_meta'=>$variant_meta_json, // voor info of latere orderkoppeling
      'variant_key' =>$variant_key,
      // Laat 'product_image' toe te posten (niet verplicht). UI gebruikt dit.
      'product_image'=>isset($_POST['product_image'])?trim((string)$_POST['product_image']):null
    ];
    $_SESSION['cart']['items'][]=$item;
  }

  back($CART_URL);
}

// ===== BULK UPDATE =====
if($action==='bulk_update'){
  // Verwijderen
  if(!empty($_POST['remove_id'])){
    if($IS_PARTNER){
      $cart_id=get_or_create_cart_id($DB,$partner_id);
      $rid=(int)$_POST['remove_id'];
      if($st=$DB->prepare("DELETE FROM cart_items WHERE id=? AND cart_id IN(SELECT id FROM carts WHERE id=? AND partner_id=?)")){
        $st->bind_param('iii',$rid,$cart_id,$partner_id); $st->execute(); $st->close();
      }
    }else{
      // Bij sessiecart verwijzen we naar array-index (id is index of meegegeven hidden index)
      $idx=(int)$_POST['remove_id'];
      if(isset($_SESSION['cart']['items'][$idx])) array_splice($_SESSION['cart']['items'],$idx,1);
    }
  }

  // Aantallen bijwerken
  if(!empty($_POST['qty'])&&is_array($_POST['qty'])){
    if($IS_PARTNER){
      $cart_id=get_or_create_cart_id($DB,$partner_id);
      foreach($_POST['qty'] as $id=>$q){
        $id=(int)$id; $q=max(1,(int)$q);
        if($st=$DB->prepare("UPDATE cart_items SET qty=?,updated_at=NOW()
                             WHERE id=? AND cart_id IN(SELECT id FROM carts WHERE id=? AND partner_id=?)")){
          $st->bind_param('iiii',$q,$id,$cart_id,$partner_id); $st->execute(); $st->close();
        }
      }
    }else{
      // In de publieke cart gebruiken we array-indexen als sleutels in het formulier
      foreach($_POST['qty'] as $idx=>$q){
        $idx=(int)$idx; $q=max(1,(int)$q);
        if(isset($_SESSION['cart']['items'][$idx])) $_SESSION['cart']['items'][$idx]['qty']=$q;
      }
    }
  }

  back($CART_URL);
}

// ===== CLEAR =====
if($action==='clear'){
  if($IS_PARTNER){
    $cart_id=get_or_create_cart_id($DB,$partner_id);
    if($st=$DB->prepare("DELETE FROM cart_items WHERE cart_id IN(SELECT id FROM carts WHERE id=? AND partner_id=?)")){
      $st->bind_param('ii',$cart_id,$partner_id); $st->execute(); $st->close();
    }
  }else{
    $_SESSION['cart']['items']=[];
  }
  back($CART_URL);
}

// Fallback
back($CART_URL);
