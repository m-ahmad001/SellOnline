<?php
session_start();
if (!isset($_SESSION['manager'])) {
    die("Access denied. Please log in as a manager.");
}

$xml = new DOMDocument();
$xml->load('goods.xml');

$items = $xml->getElementsByTagName('item');
$processedCount = 0;

for ($i = $items->length - 1; $i >= 0; $i--) {
    $item = $items->item($i);
    $quantity = $item->getElementsByTagName('quantity')->item(0);
    if ($quantity->nodeValue == "0") {
        $item->parentNode->removeChild($item);
        $processedCount++;
    }
}

if ($processedCount > 0) {
    $xml->save('goods.xml');
    echo "Processed $processedCount sold item(s) successfully.";
} else {
    echo "No sold items to process.";
}
?>
