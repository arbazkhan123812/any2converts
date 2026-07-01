<?php
require 'app/Support/tool_handlers.php';
$html = getBackgroundRemoverHTML();
preg_match('/<script>(.*?)<\/script>/s', $html, $m);
file_put_contents('test_script.js', $m[1]);
echo "Saved test_script.js\n";
