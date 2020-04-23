<?php
$redirectStr = '';
if(!empty($_GET['paymentID']) && !empty($_GET['token']) && !empty($_GET['payerID']) && !empty($_GET['pid']) ){
    // Include and initialize database class
    include 'DB.class.php';
    $db = new DB;

    // Include and initialize paypal class
    include 'PaypalExpress.class.php';
    $paypal = new PaypalExpress;
    
    // Get payment info from URL
    $paymentID = $_GET['paymentID'];
    $token = $_GET['token'];
    $payerID = $_GET['payerID'];
    $productID = $_GET['pid'];
    
    // Validate transaction via PayPal API
    $paymentCheck = $paypal->validate($paymentID, $token, $payerID, $productID);
    
    // If the payment is valid and approved
    if($paymentCheck && $paymentCheck->state == 'approved'){

        // Get the transaction data
        $id = $paymentCheck->id;
        $state = $paymentCheck->state;
        $payerFirstName = $paymentCheck->payer->payer_info->first_name;
        $payerLastName = $paymentCheck->payer->payer_info->last_name;
        $payerName = $payerFirstName.' '.$payerLastName;
        $payerEmail = $paymentCheck->payer->payer_info->email;
        $payerID = $paymentCheck->payer->payer_info->payer_id;
        $payerCountryCode = $paymentCheck->payer->payer_info->country_code;
        $paidAmount = $paymentCheck->transactions[0]->amount->details->subtotal;
        $currency = $paymentCheck->transactions[0]->amount->currency;
        
        // Get product details
        $conditions = array(
            'where' => array('id' => $productID),
            'return_type' => 'single'
        );
        $productData = $db->getRows('products', $conditions);
        
        // If payment price is valid
        if($productData['price'] >= $paidAmount){
            
            // Insert transaction data in the database
            $data = array(
                'product_id' => $productID,
                'txn_id' => $id,
                'payment_gross' => $paidAmount,
                'currency_code' => $currency,
                'payer_id' => $payerID,
                'payer_name' => $payerName,
                'payer_email' => $payerEmail,
                'payer_country' => $payerCountryCode,
                'payment_status' => $state
            );
            $insert = $db->insert('payments', $data);
            
            // Add insert id to the URL
            $redirectStr = '?id='.$insert;
        }
    }
    
    // Redirect to payment status page
    header("Location:payment-status.php".$redirectStr);
}else{
    // Redirect to the home page
    header("Location:index.php");
}
?>