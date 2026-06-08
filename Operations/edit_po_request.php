<?php 	
		$id =$_REQUEST['id'];
require_once("db_connect.php");		
		$stmt_select="SELECT * from purchase where id='$id'";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			echo $row["vendor_name"]."*".$row["Type_of_Vendor"]."*".$row["campaign_code"]."*".$row["total_prints"]."*".$row["per_unitcost"]."*".$row["id"]."*".$row["comments"];
		}



?>