<?php
session_start();
if (!isset($_SESSION['manager'])) {
    die("Access denied. Please log in as a manager.");
}

header('Content-Type: application/json');

$xml = new DOMDocument();
$xml->load('goods.xml');

$items = [];
$goodsList = $xml->getElementsByTagName('item');

foreach ($goodsList as $good) {
    $quantity = intval($good->getElementsByTagName('quantity')->item(0)->nodeValue);
    if ($quantity == 0) {
        $items[] = [
            'id' => $good->getElementsByTagName('id')->item(0)->nodeValue,
            'name' => $good->getElementsByTagName('name')->item(0)->nodeValue,
            'quantity' => $quantity,
        ];
    }
}

echo json_encode($items);
?>
