<?php
session_start();
if (!isset($_SESSION['manager'])) {
    die(json_encode(['error' => 'Access denied. Please log in as a manager.']));
}

header('Content-Type: application/json');

$xml = new DOMDocument();
$xml->load('orders.xml');

$orders = [];
$ordersList = $xml->getElementsByTagName('order');

foreach ($ordersList as $order) {
    $processed = $order->getAttribute('processed');
    if ($processed !== 'true') {
        $items = [];
        $itemsList = $order->getElementsByTagName('item');
        foreach ($itemsList as $item) {
            $items[] = [
                'id' => $item->getElementsByTagName('id')->item(0)->nodeValue,
                'name' => $item->getElementsByTagName('name')->item(0)->nodeValue,
                'quantity' => intval($item->getElementsByTagName('quantity')->item(0)->nodeValue),
                'price' => floatval($item->getElementsByTagName('price')->item(0)->nodeValue),
            ];
        }
        $orders[] = [
            'id' => $order->getElementsByTagName('id')->item(0)->nodeValue,
            'customer_id' => $order->getElementsByTagName('customer_id')->item(0)->nodeValue,
            'order_date' => $order->getElementsByTagName('order_date')->item(0)->nodeValue,
            'items' => $items,
            'total' => floatval($order->getElementsByTagName('total')->item(0)->nodeValue),
        ];
    }
}

echo json_encode($orders);
?>
