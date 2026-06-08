<?php 	
		$id =$_REQUEST['id'];
require_once("db_connect.php");		
		$stmt_select="SELECT * from vendor_reg where id='$id'";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			echo $row["date"]."*".$row["vendor_name"]."*".$row["phone_no"]."*".$row["GST_no"]."*".$row["pan_no"]."*".$row["name_used_tally"]."*".$row["type_of_business"]."*".$row["Credit_Period"]."*".$row["Credit_Limit"]."*".$row["Type_of_Payment"]."*".$row["Primary_Name"]."*".$row["Type_of_Vendor"]."*".$row["Address"]."*".$row["City"]."*".$row["State"]."*".$row["Country"]."*".$row["Zip"]."*".$row["Contact_Name"]."*".$row["E_mail"]."*".$row["Phone"]."*".$row["Mobile"]."*".$row["Fax"];
			   }



?>