<?php
// export_no_token.php
// One-time use. Place in webroot, call via HTTPS, then delete.
// Optional: ?gzip=1  -> sends .gz (Content-Encoding: gzip)
// Batch size: ?batch=250

ini_set('display_errors', 0);
error_reporting(0);
set_time_limit(0);
ob_implicit_flush(true);

// Load existing ini.inc (expects variables like $db_host, $db_user, $db_pass, $db_name)
$iniPath = __DIR__ . '/ini.inc';
if (!file_exists($iniPath)) {
    http_response_code(500);
    echo "ini.inc not found.";
    exit;
}
@include_once $iniPath;

// Basic validation
if (!isset($db_host) || !isset($db_user) || !isset($db_pass) || !isset($db_name)) {
    http_response_code(500);
    echo "ini.inc missing DB credentials.";
    exit;
}

// Options
$use_gzip = (isset($_GET['gzip']) && $_GET['gzip'] == '1');
$batch_size = isset($_GET['batch']) ? (int)$_GET['batch'] : 250;
if ($batch_size <= 0) $batch_size = 250;

// Connect
$mysqli = @new mysqli($db_host, $db_user, $db_pass, $db_name);
if ($mysqli->connect_error) {
    http_response_code(500);
    echo "DB connect error.";
    exit;
}
$mysqli->set_charset('utf8mb4');

// Headers & filename
$timestamp = date('Y-m-d_H-i-s');
$filename_base = "{$db_name}_backup_{$timestamp}.sql";
if ($use_gzip) {
    header('Content-Type: application/octet-stream');
    header('Content-Encoding: gzip');
    header('Content-Disposition: attachment; filename="' . $filename_base . '.gz"');
} else {
    header('Content-Type: application/sql; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename_base . '"');
}

// flush buffers
while (ob_get_level() > 0) ob_end_flush();

// streaming helper (buffer then gzencode if needed)
$chunk_buffer = '';
function stream_out($str) {
    global $use_gzip, $chunk_buffer;
    if ($use_gzip) {
        $chunk_buffer .= $str;
        if (strlen($chunk_buffer) >= 512 * 1024) {
            echo gzencode($chunk_buffer, 6);
            $chunk_buffer = '';
        }
    } else {
        echo $str;
    }
    flush();
    if (connection_aborted()) exit;
}

// meta
$meta = "-- MySQL dump\n-- Host: {$db_host}\n-- Generation Time: " . date('r') . "\n-- Database: {$db_name}\n\n";
stream_out($meta);

// list tables
$tables = [];
$res = $mysqli->query("SHOW TABLES");
if ($res) {
    while ($row = $res->fetch_row()) $tables[] = $row[0];
    $res->free();
} else {
    http_response_code(500);
    echo "Failed to list tables.";
    $mysqli->close();
    exit;
}

foreach ($tables as $table) {
    // structure
    $res = $mysqli->query("SHOW CREATE TABLE `{$table}`");
    if ($res && $row = $res->fetch_assoc()) {
        stream_out("-- ----------------------------\n");
        stream_out("-- Table structure for `{$table}`\n");
        stream_out("-- ----------------------------\n\n");
        stream_out("DROP TABLE IF EXISTS `{$table}`;\n");
        stream_out($row['Create Table'] . ";\n\n");
        $res->free();
    } else {
        continue;
    }

    // data (unbuffered)
    stream_out("-- ----------------------------\n");
    stream_out("-- Data for table `{$table}`\n");
    stream_out("-- ----------------------------\n\n");

    $use_result = $mysqli->query("SELECT * FROM `{$table}`", MYSQLI_USE_RESULT);
    if ($use_result === false) continue;

    $cols = [];
    $values_batch = [];
    $firstRow = $use_result->fetch_assoc();
    if ($firstRow !== null) {
        $cols = array_keys($firstRow);
        $vals = array_map(function($v) use ($mysqli){
            if ($v === null) return 'NULL';
            return "'" . $mysqli->real_escape_string($v) . "'";
        }, array_values($firstRow));
        $values_batch[] = "(" . implode(",", $vals) . ")";

        while ($row = $use_result->fetch_assoc()) {
            $vals = array_map(function($v) use ($mysqli){
                if ($v === null) return 'NULL';
                return "'" . $mysqli->real_escape_string($v) . "'";
            }, array_values($row));
            $values_batch[] = "(" . implode(",", $vals) . ")";

            if (count($values_batch) >= $batch_size) {
                $cols_esc = array_map(function($c){ return "`{$c}`"; }, $cols);
                stream_out("INSERT INTO `{$table}` (" . implode(",", $cols_esc) . ") VALUES\n");
                stream_out(implode(",\n", $values_batch) . ";\n");
                $values_batch = [];
            }
        }

        if (count($values_batch) > 0) {
            $cols_esc = array_map(function($c){ return "`{$c}`"; }, $cols);
            stream_out("INSERT INTO `{$table}` (" . implode(",", $cols_esc) . ") VALUES\n");
            stream_out(implode(",\n", $values_batch) . ";\n");
            $values_batch = [];
        }
    }
    $use_result->free();
    stream_out("\n");
}

// finish gzip buffer
if ($use_gzip && strlen($chunk_buffer) > 0) {
    echo gzencode($chunk_buffer, 6);
    $chunk_buffer = '';
}

stream_out("-- Dump complete\n");
$mysqli->close();
exit;
