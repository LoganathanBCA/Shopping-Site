<?php
// include database configuration file
include 'dbConfig.php';
// initializ shopping cart class
include 'cart.php';
$cart = new Cart;
	if(!isset($_SESSION["UID"]))
	{
		$_SESSION["page"]="checkout.php";
		echo "<script>window.open('login.php?err=Please Login To Shop..','_self');</script>";
	}
	
	


// redirect to home if cart is empty
if($cart->total_items() <= 0){
    header("Location: viewProduct.php");
}

// set customer ID in session
//$_SESSION['sessCustomerID'] = 1;

// get customer details by session customer ID
$query = $db->query("SELECT * FROM user_reg WHERE UID = ".$_SESSION['UID']);
$custRow = $query->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout</title>

	<?php 
		include "head.php";
	?>
	
    <style>
    .table{width: 65%;float: left;}
    .shipAddr{width: 30%;float: left;margin-left: 30px;}
    .footBtn{width: 95%;float: left;}
    .orderBtn {float: right;}
    </style>
</head>
<body>
<?php 
	include "topnav.php";
?>
<div class="container" style="margin-top:70px;">
    <h1 class="text-primary">Order Preview</h1>
    <table class="table">
    <thead>
        <tr class="text-primary">
            <th>Product</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if($cart->total_items() > 0){
            //get cart items from session
            $cartItems = $cart->contents();
            foreach($cartItems as $item){
        ?>
        <tr>
            <td><?php echo $item["name"]; ?></td>
            <td><?php echo 'Rs.'.$item["price"].' '; ?></td>
            <td><?php echo $item["qty"]; ?></td>
            <td><?php echo 'Rs.'.$item["subtotal"].''; ?></td>
        </tr>
        <?php } }else{ ?>
        <tr><td colspan="4"><p>No items in your cart......</p></td>
        <?php } ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="3"></td>
            <?php if($cart->total_items() > 0){ ?>
            <td class="text-center"><strong>Total <?php echo 'Rs.'.$cart->total().' '; ?></strong></td>
           <tr>
		   <?php
	if(isset($_POST["psubmit"]))
	{
	//	print_r($_POST);
		if($_POST["cash"]==$cart->total()  && $_POST["pmode"]=="cash")
		{
		header("location:cartAction.php?action=placeOrder");
		}
		else if($_POST["cash"]==$cart->total()  && $_POST["pmode"]=="online")
		{
		header("location:onpayment.php?amt={$_POST["cash"]}");
		}
		else{
		echo"<div class='alert alert-danger'>Please Paid Full Amount Single Payment Only Available..!</div>";
		}
	}
	?>
		   <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
			 <td colspan="3"><div class="row">
			<div class="col-md-12">
				<label>Mode of Payment</label>
				<select class='form-control' name='pmode'> 
					<option value="cash">Cash</option>
					<option value="online">Online Payment</option>
				</select>
			</div>
			</div></td>
			<td colspan="3"><div class="row">
			<div class="col-md-12">
				<label>Enter Amount</label>
				<input type="text" class='form-control' name='cash'>
			</div>
			</div></td>
			
			</tr>
			<?php } ?>
        </tr>
    </tfoot>
    </table>
    <!--<div class="shipAddr">
        <h4>Shipping Details</h4>
        <p><?php echo $custRow['NAME']; ?></p>
        <p><?php echo $custRow['REGISTER']; ?></p>
    </div>-->
    <div class="footBtn">
        <a href="product.php" class="btn btn-warning"><i class="glyphicon glyphicon-menu-left"></i> Continue Shopping</a>
        <button type="submit" name="psubmit"class="btn btn-success orderBtn">Place Order <i class="glyphicon glyphicon-menu-right"></i></button>
   <!--<a href="cartAction.php?action=placeOrder" class="btn btn-success orderBtn">Place Order <i class="glyphicon glyphicon-menu-right"></i></a>-->
    </div>
	</form>
	
</div>
</body>
</html>