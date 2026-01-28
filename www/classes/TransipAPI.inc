<?php
// FILE: /classes/TransipAPI.inc

namespace classes;

class TransipAPI {
    private string $login = "gielenmatthias"; // jouw TransIP login
    private string $privateKeyPath = "/var/www/webcrafters.be/etc/keys/transip.key";
    private ?string $token = null;

    /**
     * Vraag een nieuw access token op via TransIP API v6
     */
    private function getToken(): string {
        if ($this->token) {
            return $this->token;
        }

        $privateKey = file_get_contents($this->privateKeyPath);
        if (!$privateKey) {
            throw new \Exception("❌ Kon private key niet openen: {$this->privateKeyPath}");
        }

        $nonce = base64_encode(random_bytes(16));
        $payload = [
            "login"     => $this->login,
            "nonce"     => $nonce,
            "read_only" => false
        ];
        $data = json_encode($payload, JSON_UNESCAPED_SLASHES);

        // Onderteken request met RSA-SHA512
        if (!openssl_sign($data, $signature, $privateKey, OPENSSL_ALGO_SHA512)) {
            throw new \Exception("❌ Kon request niet ondertekenen");
        }
        $signature = base64_encode($signature);

        $ch = curl_init("https://api.transip.nl/v6/auth");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "Signature: {$signature}"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception("cURL error: " . curl_error($ch));
        }
        curl_close($ch);

        $result = json_decode($response, true);
        if (!isset($result['token'])) {
            throw new \Exception("❌ Geen token ontvangen: " . $response);
        }

        $this->token = $result['token'];
        return $this->token;
    }

    /**
     * Generieke request helper
     */
    private function request(string $method, string $endpoint, array $body = null): array {
        $token = $this->getToken();
        $url = "https://api.transip.nl/v6/" . ltrim($endpoint, "/");

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            "Content-Type: application/json"
        ]);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception("cURL error: " . curl_error($ch));
        }
        curl_close($ch);

        $data = json_decode($response, true);
        if (!$data) {
            throw new \Exception("❌ Ongeldig antwoord van TransIP: " . $response);
        }

        return $data;
    }

    /**
     * Controleer beschikbaarheid van domein
     */
    public function checkDomain(string $domain): object {
        $data = $this->request("GET", "domains/{$domain}/availability");
        return (object)$data;
    }

    /**
     * Haal alle TLD’s op
     */
    public function getAllTLDs(): array {
        $data = $this->request("GET", "tlds");
        return $data['tlds'] ?? [];
    }

    /**
     * Bestel een domein
     */
    public function orderDomain(string $domain, array $contacts, array $nameservers = []): array {
        $body = [
            "domainName"  => $domain,
            "contacts"    => $contacts,
            "nameservers" => $nameservers
        ];

        return $this->request("POST", "domain-orders", $body);
    }
}
