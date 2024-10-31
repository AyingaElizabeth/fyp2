<?php
require('./top.php');
require('functions.inc.php');

if(!isset($_SESSION['ORDER_ID'])){
    header('location:index.php');
    exit();
}

$order_id = $_SESSION['ORDER_ID'];
$order_details = get_order_details($con, $order_id);

unset($_SESSION['ORDER_ID']);
?>

<div class="payment-failed-section">
    <h1>Payment Failed</h1>
    <p>We're sorry, but your payment for order (ID: <?php echo $order_id; ?>) could not be processed.</p>
    <p>Total Amount: <?php echo $order_details['total_price']; ?></p>
    <p>Please try again or contact our customer support for assistance.</p>
    <a href="checkout.php" class="btn">Try Again</a>
</div>

<?php
require('footer.php');
?>