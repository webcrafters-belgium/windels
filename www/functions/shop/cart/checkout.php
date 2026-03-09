<?php
declare(strict_types=1);
session_start();
ini_set('display_errors','1'); error_reporting(E_ALL);

require $_SERVER['DOCUMENT_ROOT'].'/ini.inc';
require $_SERVER['DOCUMENT_ROOT'].'/lib/mollie/vendor/autoload.php';

use Mollie\Api\MollieApiClient;

header('Content-Type: application/json');

// Mollie API client
$mollie = new MollieApiClient();
$mollie->setApiKey($mollie_key);

// JSON payload inlezen
$raw  = file_get_contents('php://input');
$data = json_decode($raw, true);
if (!is_array($data)) {
    echo json_encode(['success'=>false,'error'=>'Ongeldige JSON payload']); exit;
}

// verplichte velden (shipment_id verwijderd!)
$required = ['shippingCost','shippingMethod','name','email','street','number','zipcode','city','country','phone'];
foreach ($required as $f) {
    if (!isset($data[$f]) || trim((string)$data[$f]) === '') {
        echo json_encode(['success'=>false,'error'=>"Veld '{$f}' ontbreekt."]); exit;
    }
}

// JSON velden uitlezen
$session_id     = session_id();
$originalShippingCost = (float)$data['shippingCost'];
$shippingMethod = $data['shippingMethod'];
$name     = trim($data['name']);
$email    = trim($data['email']);
$phone    = trim($data['phone']);
$street   = trim($data['street']);
$number   = trim($data['number']);
$zipcode  = trim($data['zipcode']);
$city     = trim($data['city']);
$country  = trim($data['country']);

// Winkelwagen ophalen
$cartStmt = $conn->prepare("SELECT product_id, quantity, price FROM cart_items WHERE session_id=?");
$cartStmt->bind_param('s',$session_id);
$cartStmt->execute();
$res = $cartStmt->get_result();
if ($res->num_rows===0) {
    echo json_encode(['success'=>false,'error'=>'Winkelwagen is leeg.']); exit;
}

$totalProducts = 0.0;
$items = [];
while ($row=$res->fetch_assoc()) {
    $line = $row['price'] * $row['quantity'];
    $items[] = [
        'id'=>$row['product_id'],
        'quantity'=>(int)$row['quantity'],
        'price'=>(float)$row['price'],
        'total'=>$line
    ];
    $totalProducts += $line;
}
$cartStmt->close();

// Couponkorting
$couponDiscount = 0.0;
if (!empty($_SESSION['applied_coupon'])
    && isset($_SESSION['applied_coupon']['discount'], $_SESSION['applied_coupon']['discount_type'])) {
    $discountValue = (float)$_SESSION['applied_coupon']['discount'];
    $rawType = strtolower((string)$_SESSION['applied_coupon']['discount_type']);
    $discountType = in_array($rawType, ['percent', 'percentage'], true) ? 'percent' : 'amount';
    if ($discountType==='percent') $couponDiscount = $totalProducts * ($discountValue/100);
    if ($discountType==='amount')  $couponDiscount = min($discountValue, $totalProducts);
}
$totalAfterCoupon = max(0, $totalProducts - $couponDiscount);

// Verzendkorting
$discountSteps = floor($totalAfterCoupon/50);
$discountPct   = min($discountSteps*10, 100);
$shippingDiscount = $originalShippingCost * ($discountPct/100);
$shippingCost     = round($originalShippingCost - $shippingDiscount,2);

// Eindtotaal
$totalAmount = round($totalAfterCoupon + $shippingCost,2);
if ($totalAmount<=0) {
    echo json_encode(['success'=>false,'error'=>'Totaalbedrag moet groter zijn dan €0,00.']); exit;
}

// Klant aanmaken of koppelen
$parts = explode(' ',$name,2);
$firstName=$parts[0]??''; $lastName=$parts[1]??'';
$customer_id=null;
$check=$conn->prepare("SELECT id FROM customers WHERE email=? LIMIT 1");
$check->bind_param("s",$email); $check->execute(); $check->bind_result($customer_id); $check->fetch(); $check->close();
if(!$customer_id){
    $ins=$conn->prepare("INSERT INTO customers (first_name,last_name,email,phone,address,house_number,postal_code,city,country) VALUES (?,?,?,?,?,?,?,?,?)");
    $ins->bind_param("sssssssss",$firstName,$lastName,$email,$phone,$street,$number,$zipcode,$city,$country);
    $ins->execute(); $customer_id=$ins->insert_id; $ins->close();
}

// Order in DB
$user_id=$_SESSION['user_id']??0;
$status='pending'; $notes='Nieuwe bestelling';
$stmt=$conn->prepare("INSERT INTO orders
(session_id,user_id,customer_id,name,email,street,number,zipcode,city,country,phone,
 shipping_method,shipping_cost,total_price,status,created_at,admin_notes)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,NOW(),?)");
$stmt->bind_param(
    'siisssssssssddss',
    $session_id,$user_id,$customer_id,$name,$email,$street,$number,$zipcode,$city,
    $country,$phone,$shippingMethod,$shippingCost,$totalAmount,$status,$notes
);

$stmt->execute(); $orderId=$stmt->insert_id; $stmt->close();

// Order-items
$ins=$conn->prepare("INSERT INTO order_items (order_id,product_id,quantity,unit_price,total_price,session_id,created_at,updated_at) VALUES (?,?,?,?,?,?,NOW(),NOW())");
foreach($items as $it){
    $ins->bind_param('iiidds',$orderId,$it['id'],$it['quantity'],$it['price'],$it['total'],$session_id);
    $ins->execute();
}
$ins->close();

// Notificatie
$msg="Je bestelling (#{$orderId}) is aangemaakt en wacht op betaling.";
$notif=$conn->prepare("INSERT INTO notifications (user_id,message,created_at) VALUES (?,?,NOW())");
$notif->bind_param('is',$user_id,$msg); $notif->execute(); $notif->close();

// Mollie betaling starten
try {
    $payment=$mollie->payments->create([
        'amount'=>['currency'=>'EUR','value'=>number_format($totalAmount,2,'.','')],
        'description'=>"Bestelling #$orderId",
        'redirectUrl'=>"https://windelsgreen-decoresin.com/pages/shop/shopping-cart/checkout-success.php?order_id=$orderId",
        'webhookUrl'=>"https://windelsgreen-decoresin.com/functions/shop/cart/mollie_webhook.php?key=$mollie_webhook_key",
        'metadata'=>['order_id'=>$orderId]
    ]);
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo json_encode(['success'=>false,'error'=>'Betaling mislukt: '.$e->getMessage()]); exit;
}

// Winkelwagen legen
$del=$conn->prepare("DELETE FROM cart_items WHERE session_id=?");
$del->bind_param('s',$session_id); $del->execute(); $del->close();

// Sessiedata
$_SESSION['last_order_id']=$orderId;
$_SESSION['last_total_price']=$totalAmount;
$_SESSION['payment_id']=$payment->id;

$conn->close();

// Response
echo json_encode(['success'=>true,'redirect'=>$payment->getCheckoutUrl()]);
