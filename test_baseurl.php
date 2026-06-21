<?php
echo "<pre>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "\n";
$sn = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
if (preg_match('#^(.*?/Web2)(/|$)#i', $sn, $m)) {
    echo "BASE_URL = " . $m[1] . "\n";
} else {
    echo "No match — fallback\n";
}
echo "</pre>";
