<?php
include dirname($_SERVER['DOCUMENT_ROOT']).'/secure/ini.inc';
require_once dirname($_SERVER['DOCUMENT_ROOT']).'/vendor/autoload.php';

use Dotenv\Dotenv;
use Mollie\Api\MollieApiClient;

$dotenv = Dotenv::createImmutable(dirname($_SERVER['DOCUMENT_ROOT']));
$dotenv->load();

$MOLLIE_KEY = $_ENV['MOLLIE_API_KEY'] ?? getenv('MOLLIE_API_KEY');
if (!isset($mysqli) || empty($MOLLIE_KEY)) { http_response_code(500); exit; }

$mollie = new MollieApiClient();
$mollie->setApiKey($MOLLIE_KEY);

// Mollie post altijd id in body
$paymentId = $_POST['id'] ?? '';
if ($paymentId === '') { http_response_code(400); exit('no id'); }

try {
  $payment = $mollie->payments->get($paymentId);
  $status  = $payment->status ?? '';
  $method  = $payment->method ?? null;
  $paidAt  = ($payment->isPaid() || (method_exists($payment,'isAuthorized') && $payment->isAuthorized()))
              ? date('Y-m-d H:i:s') : null;

  $stmt=$mysqli->prepare("UPDATE mollie_payments SET status=?, method=?, paid_at=? WHERE payment_id=?");
  $stmt->bind_param('ssss',$status,$method,$paidAt,$paymentId);
  $stmt->execute(); $stmt->close();

  http_response_code(200);
} catch (Throwable $e) {
  error_log("[WEBHOOK] ".$e->getMessage());
  http_response_code(200); // altijd 200 naar Mollie
}
