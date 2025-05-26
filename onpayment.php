<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Free Payment Gateway</title>
</head>
<body>

<h2>Pay with Stripe (Test Mode)</h2>

<form action="charge.php" method="POST">
    <script src="https://checkout.stripe.com/checkout.js" 
            class="stripe-button"
            data-key="pk_test_51J..."
            data-amount=<?phpecho $_GET["amt"]; ?>
            data-name="Test Payment"
            data-description="Pay <?phpecho $_GET["amt"]; ?>"
            data-currency="usd">
    </script>
</form>

</body>
</html>
