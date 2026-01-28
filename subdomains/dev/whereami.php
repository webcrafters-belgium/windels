<?php
error_reporting(E_ALL);
header("Content-Type: text/plain; charset=utf-8");
echo "__DIR__: ".__DIR__."\n";
echo "DOCUMENT_ROOT: ".($_SERVER["DOCUMENT_ROOT"]??"n/a")."\n";
echo "SCRIPT_NAME: ".$_SERVER["SCRIPT_NAME"]."\n";
echo "file_exists(.maintenance): ".(file_exists(__DIR__."/.maintenance")?"YES":"NO")."\n";
