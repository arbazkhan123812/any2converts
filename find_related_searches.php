<?php
$th = file_get_contents('app/Support/tool_handlers.php');
$lines = explode("\n", $th);
foreach ($lines as $i => $l) {
    if (stripos($l, 'Related Searches') !== false) {
        echo "Line " . ($i+1) . ": " . substr(trim($l), 0, 100) . "...\n";
    }
}
