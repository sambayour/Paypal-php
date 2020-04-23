<?php
// Include and initialize database class
include 'DB.class.php';
$db = new DB;

// Get all products
$products = $db->getRows('products');
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon"  />
        <meta name="description" content="Live Demo at https://samuelolubayo.website/paypal/ - PayPal Express Checkout Integration in PHP by https://samuelolubayo.website/paypal/">
        <meta name="keywords" content="demo, https://samuelolubayo.website/paypal/ demo, project demo, live demo, tutorials, programming, coding">
        <meta name="author" content="https://samuelolubayo.website/paypal/">
        <title>Live Demo - PayPal </title>
        <!-- Bootstrap core CSS -->
        <link href="bootstrap.css" rel="stylesheet">
        <!-- Add custom CSS here -->
        <link href="style.css" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
</head>
<body>
<?php
// List all products
if(!empty($products)){
    foreach($products as $row){
?>
        <div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-4 col-lg-4 col-md-4">
                    <div class="thumbnail">
    <img src="images/<?php echo $row['image']; ?>"/>
</div>
    <p>Name: <?php echo $row['name']; ?></p>
    <p>Price: <?php echo $row['price']; ?></p>
    <a href="checkout.php?id=<?php echo $row['id']; ?>">BUY</a>
    <div id="paypal-button"></div>
</div>
</div>
</div></div>
</div>
<?php        
    }
}else{
    echo '<p>Product(s) not found...</p>';
}
?>
<script>
paypal.Button.render({
    // Configure environment
    env: 'sandbox',
    client: {
        sandbox: 'AeC3cMzH1BOdzgnRW9oTsqfJQt6y1LJRqjQwOxQIvV6SFmSNTBxJEN56pIIOUWfmCjI2oNPSAn4GFksd',
        production: 'AeC3cMzH1BOdzgnRW9oTsqfJQt6y1LJRqjQwOxQIvV6SFmSNTBxJEN56pIIOUWfmCjI2oNPSAn4GFksd'
    },
    // Customize button (optional)
    locale: 'en_US',
    style: {
        size: 'small',
        color: 'gold',
        shape: 'pill',
    },
    // Set up a payment
    payment: function (data, actions) {
        return actions.payment.create({
            transactions: [{
                amount: {
                    total: '1.00',
                    currency: 'USD'
                }
            }]
      });
    },
    // Execute the payment
    onAuthorize: function (data, actions) {
        return actions.payment.execute()
        .then(function () {
            // Show a confirmation message to the buyer
            //window.alert('Thank you for your purchase!');
            
            // Redirect to the payment process page
            window.location = "process.php?paymentID="+data.paymentID+"&token="+data.paymentToken+"&payerID="+data.payerID+"&pid=2";
        });
    }
}, '#paypal-button');
</script>

</body>
</html>