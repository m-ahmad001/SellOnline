<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $xml = new DOMDocument();
    $xml->load('customer.xml');

    $customers = $xml->getElementsByTagName('customer');
    $valid = false;

    foreach ($customers as $customer) {
        $customerEmail = $customer->getElementsByTagName('email')->item(0)->nodeValue;
        $customerPassword = $customer->getElementsByTagName('password')->item(0)->nodeValue;

        if ($email === $customerEmail && password_verify($password, $customerPassword)) {
            $valid = true;
            $_SESSION['customer_id'] = $customer->getElementsByTagName('id')->item(0)->nodeValue;
            $_SESSION['customer_name'] = $customer->getElementsByTagName('name')->item(0)->nodeValue;
            break;
        }
    }

    if ($valid) {
        header("Location: buying.htm");
        exit();
    } else {
        echo "Invalid credentials. Please try again.";
    }
} else {
    header("Location: login.htm");
    exit();
}
?>
