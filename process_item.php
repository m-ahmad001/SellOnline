<?php
session_start();
if (!isset($_SESSION['manager'])) {
    die("Access denied. Please log in as a manager.");
}

$data = json_decode(file_get_contents('php://input'), true);
$orderId = $data['id'];

$xml = new DOMDocument();
$xml->load('orders.xml');

$orders = $xml->getElementsByTagName('order');
$processed = false;

foreach ($orders as $order) {
    if ($order->getElementsByTagName('id')->item(0)->nodeValue == $orderId) {
        $order->setAttribute('processed', 'true');
        $processed = true;
        break;
    }
}

if ($processed) {
    $xml->save('orders.xml');
    echo "Order processed successfully.";
} else {
    echo "Order not found.";
}
?>
