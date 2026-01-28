<?php
function createShipment(mysqli $conn, int $orderId): array
{
    global $sendcloud_public_key, $sendcloud_private_key;

    // 📄 1. Orderinfo ophalen
    $stmt = $conn->prepare("
        SELECT name, company_name, email, phone, street, number, zipcode, city, country
        FROM orders
        WHERE id = ?
        LIMIT 1
    ");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        return ['success' => false, 'error' => 'Order niet gevonden.'];
    }
    $order = $result->fetch_assoc();
    $stmt->close();

    // 📦 2. Payload opbouwen
    $payload = [
        'shipment' => [
            'name'         => $order['name'],
            'company_name' => $order['company_name'] ?? '',
            'address'      => $order['street'] . ' ' . $order['number'],
            'postal_code'  => $order['zipcode'],
            'city'         => $order['city'],
            'country'      => $order['country'],
            'email'        => $order['email'],
            'telephone'    => $order['phone'],
            'order_number' => $orderId,
            'request_label' => true
        ]
    ];

    // 🚀 3. Sendcloud API call
    $ch = curl_init('https://panel.sendcloud.sc/api/v2/shipments');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, "$sendcloud_public_key:$sendcloud_private_key");
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($httpCode !== 201 || empty($data['shipment'])) {
        return ['success' => false, 'error' => $data['error']['message'] ?? 'Sendcloud-fout'];
    }

    // 💾 4. Optioneel: in je DB bijhouden
    $stmt = $conn->prepare("
        UPDATE orders
        SET shipping_id = ?, label_url = ?, shipping_status = ?
        WHERE id = ?
    ");
    $shipmentId  = $data['shipment']['id'];
    $labelUrl    = $data['shipment']['label']['label_printer'] ?? '';
    $status      = $data['shipment']['status'];
    $stmt->bind_param("sssi", $shipmentId, $labelUrl, $status, $orderId);
    $stmt->execute();
    $stmt->close();

    return [
        'success'     => true,
        'shipment_id' => $shipmentId,
        'label_url'   => $labelUrl,
        'status'      => $status
    ];
}
