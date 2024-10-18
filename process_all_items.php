<?php
session_start();
if (!isset($_SESSION['manager'])) {
    die("Access denied. Please log in as a manager.");
}

$xml = new DOMDocument();
$xml->load('orders.xml');

$orders = $xml->getElementsByTagName('order');
$processedCount = 0;

foreach ($orders as $order) {
    if ($order->getAttribute('processed') !== 'true') {
        $order->setAttribute('processed', 'true');
        $processedCount++;
    }
}

if ($processedCount > 0) {
    $xml->save('orders.xml');
    echo "Processed $processedCount order(s) successfully.";
} else {
    echo "No unprocessed orders found.";
}
?>
