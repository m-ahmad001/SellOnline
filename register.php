<?php
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $firstName = htmlspecialchars($_POST['firstName'], ENT_QUOTES, 'UTF-8');
    $lastName = htmlspecialchars($_POST['lastName'], ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];
    $retypePassword = $_POST['retypePassword'];
    $phone = htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8');

    // Server-side validation
    $errors = [];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if ($password !== $retypePassword) {
        $errors[] = "Passwords do not match";
    }

    if (!preg_match('/^(0\d{1}(\s)?\d{8})$/', $phone)) {
        $errors[] = "Invalid phone format. Use (0d)dddddddd or 0d dddddddd";
    }

    // Check if customer.xml exists, if not create it
    if (!file_exists('customer.xml')) {
        $xml = new DOMDocument('1.0', 'UTF-8');
        $root = $xml->createElement('customers');
        $xml->appendChild($root);
        $xml->save('customer.xml');
    }

    // Load the XML file
    $xml = new DOMDocument();
    $xml->load('customer.xml');

    // Check if email is unique
    $customers = $xml->getElementsByTagName('customer');
    foreach ($customers as $customer) {
        if ($customer->getElementsByTagName('email')->item(0)->nodeValue == $email) {
            $errors[] = "Email already exists";
            break;
        }
    }

    if (count($errors) > 0) {
        echo json_encode(['status' => 'error', 'message' => implode('<br>', $errors)]);
        exit;
    }

    // If no errors, proceed with registration
    $customerId = uniqid('CUST');
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $newCustomer = $xml->createElement('customer');
    $newCustomer->appendChild($xml->createElement('id', $customerId));
    $newCustomer->appendChild($xml->createElement('email', $email));
    $newCustomer->appendChild($xml->createElement('firstName', $firstName));
    $newCustomer->appendChild($xml->createElement('lastName', $lastName));
    $newCustomer->appendChild($xml->createElement('password', $hashedPassword));
    $newCustomer->appendChild($xml->createElement('phone', $phone));

    $xml->documentElement->appendChild($newCustomer);

    if ($xml->save('customer.xml')) {
        echo json_encode([
            'status' => 'success',
            'message' => "Registration successful! Your customer ID is: $customerId"
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => "Error saving customer information. Please try again."
        ]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => "Invalid request method"]);
}
?>
