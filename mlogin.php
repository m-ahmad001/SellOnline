<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $managerId = $_POST['managerId'];
    $password = $_POST['password'];

    $credentials = file('manager.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $valid = false;

    foreach ($credentials as $line) {
        list($id, $pwd) = explode(':', $line);
        if ($managerId === $id && $password === $pwd) {
            $valid = true;
            break;
        }
    }

    if ($valid) {
        $_SESSION['manager'] = $managerId;
        header("Location: manager_dashboard.htm");  // Redirect to the new dashboard
        exit();
    } else {
        echo "Invalid credentials. Please try again.";
    }
} else {
    header("Location: mlogin.htm");
    exit();
}
?>
