<?php
session_start();

if (!isset($_SESSION['manager'])) {
    die("Access denied. Please log in.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];

    // Validate inputs
    if (empty($name) || !is_numeric($price) || !is_numeric($quantity) || empty($description)) {
        die("Invalid input. Please check all fields.");
    }

    // Load the XML file
    $xml = new DOMDocument();
    $xml->load('goods.xml');

    // Generate a unique item number
    $itemNumber = uniqid('ITEM');

    // Create a new item element
    $newItem = $xml->createElement('item');
    $newItem->appendChild($xml->createElement('id', $itemNumber));
    $newItem->appendChild($xml->createElement('name', $name));
    $newItem->appendChild($xml->createElement('price', $price));
    $newItem->appendChild($xml->createElement('quantity', $quantity));
    $newItem->appendChild($xml->createElement('description', $description));

    // Append the new item to the root element
    $xml->documentElement->appendChild($newItem);

    // Save the updated XML
    if ($xml->save('goods.xml')) {
        echo "Item added successfully. Item number: " . $itemNumber;
    } else {
        echo "Error saving item. Please try again.";
    }
} else {
    header("Location: listing.htm");
    exit();
}
?>
