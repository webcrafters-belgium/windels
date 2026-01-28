<?php
function truncateHtmlPreserveTags(string $html, int $maxLength = 60): string {
    $printedLength = 0;
    $tags = [];
    $result = '';

    preg_match_all('/(<[^>]+>|[^<]+)/u', $html, $tokens);

    foreach ($tokens[0] as $token) {
        if (preg_match('/<[^>]+>/', $token)) {
            if (preg_match('/^<\s*\/(\w+)/', $token, $closingTag)) {
                array_pop($tags);
                $result .= $token;
            } elseif (preg_match('/^<\s*(\w+)/', $token, $openingTag)) {
                $tags[] = $openingTag[1];
                $result .= $token;
            } else {
                $result .= $token;
            }
        } else {
            $remaining = $maxLength - $printedLength;
            $textLength = mb_strlen($token);
            if ($textLength > $remaining) {
                $result .= mb_substr($token, 0, $remaining) . '...';
                break;
            } else {
                $printedLength += $textLength;
                $result .= $token;
            }
        }
    }

    while (!empty($tags)) {
        $result .= '</' . array_pop($tags) . '>';
    }

    return $result;
}
