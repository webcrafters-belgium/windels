<?php
// CONFIG
$baseUrl = 'https://windelsgreen-decoresin.com';
$sitemapIndex = $baseUrl . '/sitemap_index.xml';
$outputDir = __DIR__ . '/static_export';

// 🗺️ Sitemap ophalen & filteren op interne links
function getAllInternalPagesFromSitemap($sitemapUrl, $baseUrl) {
    $xml = simplexml_load_string(file_get_contents($sitemapUrl));
    $urls = [];

    // Als het een sitemap index is
    if (isset($xml->sitemap)) {
        foreach ($xml->sitemap as $s) {
            $childSitemap = (string)$s->loc;
            $urls = array_merge($urls, getAllInternalPagesFromSitemap($childSitemap, $baseUrl));
        }
    }

    // Als het een pagina-sitemap is
    if (isset($xml->url)) {
        foreach ($xml->url as $entry) {
            $loc = (string)$entry->loc;
            if (str_starts_with($loc, $baseUrl)) {
                $urls[] = $loc;
            }
        }
    }

    return $urls;
}

// 🔄 URL omzetten naar bestandsstructuur
function urlToPath($url, $baseUrl) {
    $rel = str_replace($baseUrl, '', $url);
    $rel = trim($rel, '/');

    // root → producten.php
    if ($rel === '') return 'index';

    // bijv: /shop/product-x → shop/product-x/producten.php
    return $rel . '/index';
}

// 🔍 HTML ophalen
function fetchHtml($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'StaticSiteExporter/1.0',
    ]);
    $html = curl_exec($ch);
    curl_close($ch);
    return $html;
}

// 💾 HTML opslaan
function saveHtml($path, $html) {
    $dir = dirname($path);
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    file_put_contents($path, $html);
}

// ✅ Pagina's ophalen
echo "📥 Sitemap uitlezen...\n";
$pages = getAllInternalPagesFromSitemap($sitemapIndex, $baseUrl);
echo "🔗 Gevonden pagina’s: " . count($pages) . "\n";

// 📦 Exporteren
foreach ($pages as $url) {
    $html = fetchHtml($url);
    if (!$html) {
        echo "⚠️ Niet gevonden: $url\n";
        continue;
    }

    $path = $outputDir . '/' . urlToPath($url, $baseUrl) . '.php';
    saveHtml($path, $html);

    echo "✅ Gekopieerd: $url → $path\n";
}

echo "\n🎉 Export voltooid in: $outputDir\n";
