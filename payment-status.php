<?php
if(!empty($_GET['id'])){

// Include and initialize database class
include 'DB.class.php';
$db = new DB;

// Get payment details

$conditions = array(
    'where' => array('id' => $_GET['id']),
    'return_type' => 'single'
);
$paymentData = $db->getRows('payments', $conditions);

}
?>

<?php
// List all products
if(!empty($paymentData)){
?>
    <h3>Your payment was successful.</h3>
    <p>TXN ID: <?php echo $paymentData['txn_id']; ?></p>
    <p>Paid Amount: <?php echo $paymentData['payment_gross'].' '.$paymentData['currency_code']; ?></p>
    <p>Payment Status: <?php echo $paymentData['payment_status']; ?></p>
    <p>Payment Date: <?php echo $paymentData['created']; ?></p>
<?php        
}else{
    echo '<p>Payment was unsuccessful</p>';
}
?>
<a href="index.php">Back to Home</a>