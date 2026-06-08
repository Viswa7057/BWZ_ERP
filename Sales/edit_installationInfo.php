<?php 	
		$id =$_REQUEST['id'];
require_once("db_connect.php");		
		$stmt_select="SELECT * from order_details where id='$id'";
	                  $rslt_rs= mysqli_query($conn,$stmt_select);
					 $x = 1;		
			   while($row = mysqli_fetch_assoc($rslt_rs)) {
			echo $row["company_name"]."*".$row["contact_person_name"]."*".$row["contact_person_mobile"]."*".$row["email"]."*".$row["Campaing_Details"]."*".$row["Expected_Start_date"]."*".$row["Any_Spl_Request"]."*".$row["Media_Type"]."*".$row["Total_No_of_Vehicles"]."*".$row["Total_Value_Without_GST"]."*".$row["PO"]."*".$row["Checklist"]."*".$row["id"];
		}



?>