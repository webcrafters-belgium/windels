<?php

include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
session_start();
$IS_PARTNER = !empty($_SESSION['partner_id']);
$partner_id = $IS_PARTNER ? (int)$_SESSION['partner_id'] : 0;

// CSRF token
if(empty($_SESSION['csrf'])){ $_SESSION['csrf']=bin2hex(random_bytes(32)); }
$csrf=$_SESSION['csrf'];

// ===== Helpers =====
function h($s){ return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8'); }

function index_exists(mysqli $db,string $table,string $index): bool{
  $t=$db->real_escape_string($table); $i=$db->real_escape_string($index);
  $res=$db->query("SHOW INDEX FROM `$t` WHERE Key_name='$i'"); $ok=$res && $res->num_rows>0; if($res) $res->free(); return $ok;
}
function col_exists(mysqli $db,string $table,string $col): bool{
  $t=$db->real_escape_string($table); $c=$db->real_escape_string($col);
  $res=$db->query("SHOW COLUMNS FROM `$t` LIKE '$c'"); $ok=$res && $res->num_rows>0; if($res) $res->free(); return $ok;
}
function ensure_cart_tables(mysqli $db): void{
  // carts
  $ok1=$db->query("SHOW TABLES LIKE 'carts'"); $hasCarts=$ok1 && $ok1->num_rows>0; if($ok1) $ok1->free();
  if(!$hasCarts){
    $db->query("CREATE TABLE IF NOT EXISTS `carts`(
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `partner_id` INT NOT NULL,
      `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      UNIQUE KEY `unique_partner_cart`(`partner_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
  }
  // cart_items
  $ok2=$db->query("SHOW TABLES LIKE 'cart_items'"); $hasItems=$ok2 && $ok2->num_rows>0; if($ok2) $ok2->free();
  if(!$hasItems){
    $db->query("CREATE TABLE IF NOT EXISTS `cart_items`(
      `id` INT AUTO_INCREMENT PRIMARY KEY,
      `cart_id` INT NOT NULL,
      `product_type` ENUM('epoxy','kaars','inkoop') NOT NULL,
      `product_id` INT NOT NULL,
      `name` VARCHAR(255) NOT NULL,
      `unit_price` DECIMAL(10,2) NOT NULL,
      `qty` INT NOT NULL DEFAULT 1,
      `variant_meta` JSON NULL,
      `variant_key` VARCHAR(64) NOT NULL DEFAULT '',
      `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts`(`id`) ON DELETE CASCADE,
      KEY `idx_cart` (`cart_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
  }else{
    if(index_exists($db,'cart_items','uniq_item')) $db->query("ALTER TABLE `cart_items` DROP INDEX `uniq_item`");
    if(index_exists($db,'cart_items','uniq_cart_item_variant')) $db->query("ALTER TABLE `cart_items` DROP INDEX `uniq_cart_item_variant`");
    if(!col_exists($db,'cart_items','variant_meta')){
      $db->query("ALTER TABLE `cart_items` ADD COLUMN `variant_meta` JSON NULL");
      if($db->errno){ $db->query("ALTER TABLE `cart_items` ADD COLUMN `variant_meta` TEXT NULL"); }
    }
    if(!col_exists($db,'cart_items','variant_key')){
      $db->query("ALTER TABLE `cart_items` ADD COLUMN `variant_key` VARCHAR(64) NOT NULL DEFAULT ''");
    }else{
      $db->query("ALTER TABLE `cart_items` MODIFY `variant_key` VARCHAR(64) NOT NULL DEFAULT ''");
    }
    if(!index_exists($db,'cart_items','idx_cart')) $db->query("CREATE INDEX `idx_cart` ON `cart_items`(`cart_id`)");
  }
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

// ===== Actie-URL’s (laat dit zo, tenzij je cart_actions elders plaatste) =====
$ACTIONS_URL = $IS_PARTNER ? "/pages/account/orders/cart_actions.php"
                           : "/pages/orders/cart_actions.php";

$CHECKOUT_URL = $IS_PARTNER ? "/pages/account/orders/cart_to_order.php?csrf=".urlencode($csrf)
                            : "/pages/orders/cart_to_order.php?csrf=".urlencode($csrf);

// ===== Data laden =====
$cart_items=[]; $cart_total=0.0; $currency='<sup>€</sup>';

if($IS_PARTNER){
  // DB-pad voor partners
  $DB = null;
  if(isset($mysqli_medewerkers)&&$mysqli_medewerkers instanceof mysqli){ $DB=$mysqli_medewerkers; }
  elseif(isset($mysqli)&&$mysqli instanceof mysqli){ $DB=$mysqli; }
  if(!$DB){ http_response_code(500); die('DB-verbinding ontbreekt (mysqli).'); }

  ensure_cart_tables($DB);
  $cart_id=get_or_create_cart_id($DB,$partner_id);

  if($st=$DB->prepare("SELECT id,product_type,product_id,name,unit_price,qty,variant_meta FROM cart_items WHERE cart_id=? ORDER BY id ASC")){
    $st->bind_param('i',$cart_id);
    $st->execute();
    $st->bind_result($id,$ptype,$pid,$name,$price,$qty,$vmeta);
    while($st->fetch()){
      $line=(float)$price*(int)$qty; $cart_total+=$line;
      $vm=null; if($vmeta!==null && $vmeta!==''){ $dec=json_decode($vmeta,true); if(json_last_error()===JSON_ERROR_NONE && is_array($dec)) $vm=$dec; }
      $cart_items[]=['id'=>$id,'ptype'=>$ptype,'pid'=>$pid,'name'=>$name,'price'=>(float)$price,'qty'=>(int)$qty,'line'=>$line,'variant_meta'=>$vm,'is_db'=>true];
    }
    $st->close();
  }
}else{
  // Sessie-pad voor particulieren
  if(empty($_SESSION['cart']) || !is_array($_SESSION['cart'])) $_SESSION['cart']=['items'=>[],'currency'=>'<sup>€</sup>'];
  if(empty($_SESSION['cart']['items']) || !is_array($_SESSION['cart']['items'])) $_SESSION['cart']['items']=[];
  if(!empty($_SESSION['cart']['currency'])) $currency=$_SESSION['cart']['currency'];

  foreach(array_values($_SESSION['cart']['items']) as $idx=>$it){
    $name = (string)($it['title'] ?? $it['name'] ?? 'Product');
    $ptype= (string)($it['product_type'] ?? '');
    $pid  = (int)($it['product_id'] ?? 0);
    $price= (float)($it['price'] ?? 0);
    $qty  = max(1,(int)($it['qty'] ?? 1));
    $line = $price*$qty; $cart_total+=$line;

    $vm=null;
    if(isset($it['variant_meta']) && $it['variant_meta']!==''){
      $dec=is_array($it['variant_meta'])?$it['variant_meta']:json_decode((string)$it['variant_meta'],true);
      if(is_array($dec)) $vm=$dec;
    }
    // Gebruik array-index als "id" voor formulier-koppeling (remove/qty)
    $cart_items[]=['id'=>$idx,'ptype'=>$ptype,'pid'=>$pid,'name'=>$name,'price'=>$price,'qty'=>$qty,'line'=>$line,'variant_meta'=>$vm,'is_db'=>false];
  }
}
?>
<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/header.php'; ?>

<style>
body{background:url('/img/uitvaartachtergrond.jpg') no-repeat center center fixed;background-size:cover}
.cart-page{background-color:rgba(255,255,255,.92);padding:24px;border-radius:12px;box-shadow:0 4px 10px rgba(0,0,0,.05);margin:32px auto 24px;max-width:900px;width:92%;box-sizing:border-box}
.h2{font-size:22px;margin:0 0 12px}
.btn{display:inline-block;padding:8px 12px;border:1px solid #0d3b2e;border-radius:8px;background:#e6e6e6;cursor:pointer;color:#0d3b2e}
.btn:hover{opacity:.9}
.btn-primary{background:#2e2e2e;color:#fff}
.btn-danger{border-color:#b00020;background:#b00020;color:#fff}
.qty-input{width:72px;padding:6px;border:1px solid #c9c9c9;border-radius:6px}
.cart-card{border:1px solid #e6e6e6;background:#fff;border-radius:10px;padding:12px}
.cart-table{width:100%;border-collapse:collapse}
.cart-table th,.cart-table td{padding:10px;border-bottom:1px solid #eee;text-align:left;vertical-align:top}
.cart-summary{margin-top:10px;display:flex;gap:10px;justify-content:flex-end;align-items:center;flex-wrap:wrap}
.total{font-size:18px;font-weight:700}
.center{display:flex;justify-content:center}
.variant-mini{margin-top:4px;font-size:12px;color:#444}
.variant-chip{display:inline-block;background:#eef7f3;border:1px solid #cbe6d9;color:#004d36;border-radius:999px;padding:.15rem .5rem;margin-right:6px;margin-top:4px}
.variant-color{display:inline-flex;align-items:center;gap:6px;margin-top:4px}
.variant-color .swatch{width:14px;height:14px;border-radius:50%;border:1px solid rgba(0,0,0,.2);display:inline-block}
/* --- cart-table: PWA + mobiel --- */
.cart-table{width:100%;border-collapse:collapse;border-spacing:0;display:block;overflow-x:auto;-webkit-overflow-scrolling:touch;scrollbar-width:thin}
.cart-table::-webkit-scrollbar{height:8px}
.cart-table::-webkit-scrollbar-thumb{background:rgba(0,0,0,.15);border-radius:8px}
.cart-table th,.cart-table td{padding:10px;border-bottom:1px solid #eee;text-align:left;vertical-align:top;white-space:nowrap}
.cart-table tr{transition:background .15s}
.cart-table tr:hover{background:rgba(0,0,0,.03)}
.cart-table:focus{outline:2px solid #004d36;outline-offset:2px}

/* mobiel: grotere tikdoelen, iets grotere tekst, beter scrollgedrag */
@media(max-width:768px){
 .cart-table{max-width:100%;border-radius:10px}
 .cart-table th,.cart-table td{padding:12px 10px;font-size:14px}
 .cart-table td .btn{padding:10px 12px;border-radius:10px}
 .cart-table .qty-input{width:64px;font-size:14px}
}

/* ultra-smal: iets compacter */
@media(max-width:480px){
 .cart-table th,.cart-table td{padding:10px 8px;font-size:13.5px}
}

/* optioneel: dark mode, alleen voor de tabel */
@media(prefers-color-scheme:dark){
 .cart-table{background:rgba(255,255,255,.02)}
 .cart-table th,.cart-table td{border-bottom:1px solid rgba(255,255,255,.08);color:#e8e8e8}
 .cart-table tr:hover{background:rgba(255,255,255,.05)}
 .cart-table::-webkit-scrollbar-thumb{background:rgba(255,255,255,.25)}
}

</style>

<main class="cart-page">
  <h2 class="h2">Winkelwagen</h2>
  <div class="cart-card">
    <?php if(empty($cart_items)): ?>
      <img src="/img/winkelwagen.png" alt="Winkelwagen" style="display:block;margin:0 auto;max-width:250px">
      <div class="center" style="margin-top:10px">
        <a class="btn btn-primary" href="/pages/assortiment.php">➜ Naar assortiment</a>
      </div>
    <?php else: ?>
      <form method="post" action="<?= h($ACTIONS_URL) ?>">
        <input type="hidden" name="csrf" value="<?= h($csrf) ?>">
        <input type="hidden" name="action" value="bulk_update">
        <table class="cart-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Type</th>
              <th><sup>€</sup>/st</th>
              <th>Aantal</th>
              <th>Lijn</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          <?php foreach($cart_items as $it):
            $name=h($it['name']); $ptype=h($it['ptype']);
            $price=number_format($it['price'],2,',','.'); $qty=(int)$it['qty'];
            $line=number_format($it['line'],2,',','.');
            $vm=$it['variant_meta'] ?? null;

            // Variant chips
            $variantHtml='';
            if(is_array($vm) && (!empty($vm['color']) || (!empty($vm['options']) && is_array($vm['options'])))){
              $parts=[];
              if(!empty($vm['color'])){ $hex=h($vm['color']); $parts[]='<span class="variant-color"><span class="swatch" style="background:'.$hex.'"></span>'.$hex.'</span>'; }
              if(!empty($vm['options'])){ $chips=''; foreach($vm['options'] as $op){ $chips.='<span class="variant-chip">'.h($op).'</span>'; } $parts[]=$chips; }
              if($parts){ $variantHtml='<div class="variant-mini">'.implode(' ',$parts).'</div>'; }
            }

            // Input key: partners => cart_items.id (DB); particulier => array index
            $rowKey = (int)$it['id'];
          ?>
            <tr>
              <td><?= $name ?><?= $variantHtml ?></td>
              <td><?= $ptype ?></td>
              <td><?= $currency ?><?= $price ?></td>
              <td><input class="qty-input" type="number" min="1" name="qty[<?= $rowKey ?>]" value="<?= $qty ?>"></td>
              <td><?= $currency ?><?= $line ?></td>
              <td>
                <button class="btn btn-danger" name="remove_id" value="<?= $rowKey ?>" title="Verwijderen">🗑</button>
              </td>
            </tr>
          <?php endforeach; ?>
          </tbody>
        </table>

        <div class="cart-summary">
          <div class="total">Totaal: <?= $currency ?><?= number_format($cart_total,2,',','.') ?></div>
          <button class="btn" name="action" value="clear" type="submit">Legen</button>
          <button class="btn" name="action" value="bulk_update" type="submit">Bijwerken</button>
          <a class="btn btn-primary" href="<?= h($CHECKOUT_URL) ?>">Afrekenen</a>
          <a class="btn" href="/pages/assortiment.php">Verder winkelen</a>
        </div>
      </form>
    <?php endif; ?>
  </div>
</main>

<?php include dirname($_SERVER['DOCUMENT_ROOT']).'/partials/footer.php'; ?>
