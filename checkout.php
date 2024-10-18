<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    die("Access denied. Please log in.");
}

$cartData = json_decode(file_get_contents('php://input'), true);

$xml = new DOMDocument();
$xml->load('goods.xml');

$orderXml = new DOMDocument();
$orderXml->load('orders.xml');

$newOrder = $orderXml->createElement('order');
$newOrder->setAttribute('processed', 'false');

$orderId = $orderXml->createElement('id', uniqid('ORDER'));
$newOrder->appendChild($orderId);

$customerId = $orderXml->createElement('customer_id', $_SESSION['customer_id']);
$newOrder->appendChild($customerId);

$orderDate = $orderXml->createElement('order_date', date('Y-m-d H:i:s'));
$newOrder->appendChild($orderDate);

$items = $orderXml->createElement('items');
$total = 0;

foreach ($cartData as $cartItem) {
    $itemFound = false;
    foreach ($xml->getElementsByTagName('item') as $item) {
        if ($item->getElementsByTagName('id')->item(0)->nodeValue === $cartItem['id']) {
            $quantity = $item->getElementsByTagName('quantity')->item(0);
            $currentQuantity = intval($quantity->nodeValue);
            $orderedQuantity = intval($cartItem['quantity']);
            
            if ($currentQuantity >= $orderedQuantity) {
                $newQuantity = $currentQuantity - $orderedQuantity;
                $quantity->nodeValue = $newQuantity;
                
                $orderItem = $orderXml->createElement('item');
                $orderItem->appendChild($orderXml->createElement('id', $cartItem['id']));
                $orderItem->appendChild($orderXml->createElement('name', $cartItem['name']));
                $orderItem->appendChild($orderXml->createElement('price', $cartItem['price']));
                $orderItem->appendChild($orderXml->createElement('quantity', $orderedQuantity));
                $items->appendChild($orderItem);
                
                $total += $cartItem['price'] * $orderedQuantity;
                $itemFound = true;
                break;
            }
        }
    }
    if (!$itemFound) {
        die("Item not found or insufficient quantity: " . $cartItem['name']);
    }
}

$newOrder->appendChild($items);
$newOrder->appendChild($orderXml->createElement('total', $total));

$orderXml->documentElement->appendChild($newOrder);

$xml->save('goods.xml');
$orderXml->save('orders.xml');

echo json_encode(['status' => 'success', 'message' => "Order placed successfully. Total: $" . number_format($total, 2)]);
?>
