<?php 
	session_start();
	include "config.php";
	$uid=$_SESSION["UID"];
	$pid=$_GET["id"];
	
	$sql="DELETE FROM product_fav WHERE UID={$uid} and PID={$pid}";
	$con->query($sql);
	$sql="insert into product_fav (UID,PID) values({$uid},{$pid});";
	if($con->query($sql))
	{
		echo "Product Added To Your Favourite";
	}
	header("location:view_product.php?pid={$_GET["id"]}");
?>