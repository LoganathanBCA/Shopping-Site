<?php
require 'vendor/autoload.php'; // Load Stripe PHP SDK

\Stripe\Stripe::setApiKey('sk_test_51J...'); // Use Test Secret Key

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $token = $_POST['stripeToken'];

        $charge = \Stripe\Charge::create([
            "amount" => 1000, // Amount in cents (10 USD)
            "currency" => "usd",
            "source" => $token,
            "description" => "Test Payment"
        ]);

        echo "<h2>Payment Successful!</h2>";
        echo "Transaction ID: " . $charge->id;

    } catch (Exception $e) {
        echo "Payment Failed: " . $e->getMessage();
    }
}
?>
