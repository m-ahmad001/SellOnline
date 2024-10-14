<?php
// You might want to include database connection code here

$itemId = $_GET['id'];

// Fetch item details from the database
// This is a simplified example; adjust according to your database structure
$query = "SELECT * FROM items WHERE id = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$itemId]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if ($item) {
    echo json_encode($item);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Item not found']);
}
