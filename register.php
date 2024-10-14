<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $phone = $_POST['phone'];

    // Load the XML file
    $xml = new DOMDocument();
    $xml->load('customer.xml');

    // Check if email already exists
    $customers = $xml->getElementsByTagName('customer');
    foreach ($customers as $customer) {
        if ($customer->getElementsByTagName('email')->item(0)->nodeValue == $email) {
            die("Error: Email already exists");
        }
    }

    // Generate a unique customer ID
    $customerId = uniqid('CUST');

    // Create a new customer element
    $newCustomer = $xml->createElement('customer');
    $newCustomer->appendChild($xml->createElement('id', $customerId));
    $newCustomer->appendChild($xml->createElement('name', $name));
    $newCustomer->appendChild($xml->createElement('email', $email));
    $newCustomer->appendChild($xml->createElement('password', $password));
    $newCustomer->appendChild($xml->createElement('phone', $phone));

    // Append the new customer to the root element
    $xml->documentElement->appendChild($newCustomer);

    // Save the updated XML
    $xml->save('customer.xml');

    echo "Registration successful! Your customer ID is: " . $customerId;
    echo "<br><a href='buyonline.htm'>Back to Main Page</a>";
} else {
    header("Location: register.htm");
    exit();
}
?>
