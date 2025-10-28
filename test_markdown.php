<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/helpers.php';

$markdown = <<<MD
| Feature | Description | Status |
|---------|-------------|--------|
| Test 1  | Description 1 | Active |
| Test 2  | Description 2 | Beta |
MD;

echo "Input Markdown:\n";
echo $markdown;
echo "\n\n";

echo "Output HTML:\n";
echo markdown_to_html($markdown);
echo "\n";
