<?php 	
		$id =$_REQUEST['id'];
require_once("db_connect.php");		
		$stmt_select="SELECT * from users where id='$id'";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
	                 
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			echo $row["user_name"]."*".$row["email"]."*".$row["user_level"]."*".$row["id"];
		}



?>