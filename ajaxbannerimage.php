<?php
include('config.php');
session_start();
$path = "products/";
$session_id=1;
$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
{
	$name = $_FILES['photoimg']['name'];
	$size = $_FILES['photoimg']['size'];
	if(strlen($name))
	{
		list($txt, $ext) = explode(".", $name);
		if(in_array($ext,$valid_formats))
		{
			if($size<(1024*1024)) // Image size max 1 MB
			{
				$actual_image_name = time().$session_id.".".$ext;
				$tmp = $_FILES['photoimg']['tmp_name'];
				if(move_uploaded_file($tmp, $path.$actual_image_name))
				{
					$s="update products set LOC='{$actual_image_name}' where PID=".$_POST["apid"];
					mysqli_query($con,$s);
					echo "<img src='products/".$actual_image_name."' height='100%' width='100%' >";
				}
				else
				echo "failed";
			}
			else
				echo "Image file size max 1 MB"; 
		}
		else
			echo "Invalid file format.."; 
	}
	else
		echo "Please select image..!";
	exit;
}
?>