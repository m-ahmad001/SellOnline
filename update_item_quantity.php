<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id']) || !isset($data['quantity']) || !isset($data['action'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
    exit;
}

$itemId = $data['id'];
$quantity = intval($data['quantity']);
$action = $data['action'];

$xml = new DOMDocument();
$xml->load('goods.xml');

$items = $xml->getElementsByTagName('item');
$updated = false;

foreach ($items as $item) {
    if ($item->getElementsByTagName('id')->item(0)->nodeValue == $itemId) {
        $quantityElement = $item->getElementsByTagName('quantity')->item(0);
        $currentQuantity = intval($quantityElement->nodeValue);
        
        if ($action === 'increase') {
            $newQuantity = $currentQuantity + $quantity;
        } elseif ($action === 'decrease') {
            $newQuantity = $currentQuantity - $quantity;
            if ($newQuantity < 0) {
                echo json_encode(['status' => 'error', 'message' => 'Not enough stock']);
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
            exit;
        }
        
        $quantityElement->nodeValue = $newQuantity;
        $updated = true;
        break;
    }
}

if ($updated) {
    $xml->save('goods.xml');
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Item not found']);
}
?>
