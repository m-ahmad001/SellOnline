<?php
session_start();
if (!isset($_SESSION['manager'])) {
    die("Access denied. Please log in as a manager.");
}

$data = json_decode(file_get_contents('php://input'), true);
$itemId = $data['id'];

$xml = new DOMDocument();
$xml->load('goods.xml');

$items = $xml->getElementsByTagName('item');
$processed = false;

foreach ($items as $item) {
    if ($item->getElementsByTagName('id')->item(0)->nodeValue == $itemId) {
        $quantity = $item->getElementsByTagName('quantity')->item(0);
        if ($quantity->nodeValue == "0") {
            $item->parentNode->removeChild($item);
            $processed = true;
        }
        break;
    }
}

if ($processed) {
    $xml->save('goods.xml');
    echo "Item processed successfully.";
} else {
    echo "Item not found or already has stock.";
}
?>
