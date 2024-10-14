<?php
header('Content-Type: application/json');

$xml = new DOMDocument();
$xml->load('goods.xml');

$items = [];
$goodsList = $xml->getElementsByTagName('item');

foreach ($goodsList as $good) {
    $items[] = [
        'id' => $good->getElementsByTagName('id')->item(0)->nodeValue,
        'name' => $good->getElementsByTagName('name')->item(0)->nodeValue,
        'price' => floatval($good->getElementsByTagName('price')->item(0)->nodeValue),
        'quantity' => intval($good->getElementsByTagName('quantity')->item(0)->nodeValue),
    ];
}

echo json_encode($items);
?>
