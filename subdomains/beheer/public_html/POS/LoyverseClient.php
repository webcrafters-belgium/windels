<?php

class LoyverseClient
{
    private $apiToken;
    private $apiBase = 'https://api.loyverse.com/v1.0/';

    public function __construct($apiToken)
    {
        $this->apiToken = $apiToken;
    }

    private function request($endpoint, $method = 'GET', $data = [])
    {
        $url = $this->apiBase . $endpoint;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->apiToken,
            'Content-Type: application/json'
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception('cURL Error: ' . curl_error($ch));
        }

        curl_close($ch);

        if ($httpCode >= 400) {
            throw new Exception("HTTP Error: $httpCode, Response: $response");
        }

        return json_decode($response, true);
    }

    // Klant toevoegen
    public function addCustomer($data)
    {
        return $this->request('customers', 'POST', $data);
    }

    // Klant bewerken
    public function updateCustomer($data)
    {
        return $this->request('customers', 'POST', $data);
    }

    // Klant verwijderen
    public function deleteCustomer($id)
    {
        return $this->request("customers/$id", 'DELETE');
    }

    // Klanten ophalen
    public function getCustomers($cursor = '')
    {
        $endpoint = 'customers' . ($cursor ? "?cursor=$cursor" : '');
        return $this->request($endpoint);
    }

    // Haal klantinformatie op
    public function getCustomer($id)
    {
        return $this->request("customers/$id");
    }

    // Haal bonnen van een klant op
    public function getCustomerReceipts($customer_id)
    {
        $endpoint = "receipts?customer_id=$customer_id";
        $response = $this->request($endpoint);
        return $response['receipts'] ?? [];
    }

    public function getReceiptsByCustomer($customer_id, $cursor = '')
    {
        $endpoint = 'receipts?customer_id=' . $customer_id;
        if (!empty($cursor)) {
            $endpoint .= '&cursor=' . $cursor;
        }
        return $this->request($endpoint);
    }

    public function getReceipts($customer_id = null, $from_date = null, $cursor = '')
    {
        $endpoint = 'receipts';
        $query = [];

        if ($customer_id) {
            $query['customer_id'] = $customer_id;
        }

        if ($from_date) {
            $query['created_at_min'] = $from_date;
        }

        if ($cursor) {
            $query['cursor'] = $cursor;
        }

        $query_string = http_build_query($query);
        $endpoint .= $query_string ? '?' . $query_string : '';

        return $this->request($endpoint);
    }

}


?>
