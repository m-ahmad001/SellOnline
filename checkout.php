<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    die("Please log in to checkout.");
}

$cart = json_decode(file_get_contents('php://input'), true);

if (empty($cart)) {
    die("Your cart is empty.");
}

$xml = new DOMDocument();
$xml->load('goods.xml');

$items = $xml->getElementsByTagName('item');
$updatedItems = [];

foreach ($cart as $cartItem) {
    $found = false;
    foreach ($items as $item) {
        if ($item->getElementsByTagName('id')->item(0)->nodeValue == $cartItem['id']) {
            $quantity = $item->getElementsByTagName('quantity')->item(0);
            $newQuantity = intval($quantity->nodeValue) - $cartItem['quantity'];
            if ($newQuantity < 0) {
                die("Not enough stock for " . $cartItem['name']);
            }
            $quantity->nodeValue = $newQuantity;
            $found = true;
            break;
        }
    }
    if (!$found) {
        die("Item not found: " . $cartItem['name']);
    }
}

$xml->save('goods.xml');

echo "Checkout successful. Thank you for your purchase!";
