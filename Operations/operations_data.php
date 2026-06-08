<?php
$loginUser = $_REQUEST['loginUser'];
require_once("db_connect.php");
$date = date('Y-m-d');
if($loginUser != ''){

//Total Completed PAYMENT
 $stmt_Totaluser="SELECT count(id) as total_count from payment_request where Status='Payment Done';";
	                           $rslt_Totaluser= mysqli_query($conn,$stmt_Totaluser);
	                           $row_Totaluser= mysqli_fetch_row($rslt_Totaluser);
							   $Total_sales = $row_Totaluser[0];
							   
//Total New PAYMENT
 $stmt_NEW_sale="SELECT count(id) as new_count from payment_request where Status='Requested';";
	                           $rslt_NewSale= mysqli_query($conn,$stmt_NEW_sale);
	                           $row_NewSale= mysqli_fetch_row($rslt_NewSale);
							   $Total_NewSale = $row_NewSale[0];
							   
//Total Rejected PAYMENT
 $stmt_Reject_sale="SELECT count(id) as reject_count from payment_request where Director_status='Rejected';";
	                           $rslt_RejectSale= mysqli_query($conn,$stmt_Reject_sale);
	                           $row_RejectSale= mysqli_fetch_row($rslt_RejectSale);
							   $Total_RejectSale = $row_RejectSale[0];


//Total Completed PO
 $stmt_Total_PO="SELECT count(id) as total_count from purchase where status='Received';";
	                           $rslt_Total_PO= mysqli_query($conn,$stmt_Total_PO);
	                           $row_Total_PO= mysqli_fetch_row($rslt_Total_PO);
							   $Total_PO = $row_Total_PO[0];
							   
//Total New PO
 $stmt_NEW_PO="SELECT count(id) as new_count from purchase where status='Requested';";
	                           $rslt_New_PO= mysqli_query($conn,$stmt_NEW_PO);
	                           $row_New_PO= mysqli_fetch_row($rslt_New_PO);
							   $Total_New_PO = $row_New_PO[0];

							   
							   echo $Total_sales;
							   echo "**".$Total_NewSale;
							   echo "**".$Total_RejectSale;
							   
							   echo "**".$Total_PO;
							   echo "**".$Total_New_PO;
							   echo "**";
							   
							   ?>

 <style>
.dt-head-center {text-align: center;}
.modal-content {
width: 138%;
}
</style>		
	<!-- ################################# PAYMENT ############################################ -->
			 <table class="table m-0 tableFixHead" id="completed_sales">
                    <thead>
                    <tr>
                     <th>Sr.No.</th>
                      <th>Vendor Name</th>
                      <th>Campaign Code</th>
					  <th>Type Of Vendor</th> 
                      <th>Invoice Value</th> 					  
					   <th>Status</th>
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from payment_request where Status='Payment Done'";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $m = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $m; ?></div></td>	
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["vendor_name"]; ?></div></td>
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["campaign_code"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["type_of_vendor"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["invoice"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Status"]; ?></div></td>
									   
									</tr>
									<?php 
								$m++;}
							?>
							 </tbody>
                  </table>		
				  
				  <?php echo "**"; ?>
				  
				  <table class="table m-0 tableFixHead" id="pending_sales">
                    <thead>
                    <tr>
                     <th>Sr.No.</th>
                      <th>Vendor Name</th>
                      <th>Campaign Code</th>
					  <th>Type Of Vendor</th> 
                      <th>Invoice Value</th> 					  
					   <th>Status</th>
					  
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from payment_request where Status='Requested'";
						
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $n = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $n; ?></div></td>	
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["vendor_name"]; ?></div></td>
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["campaign_code"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["type_of_vendor"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["invoice"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Status"]; ?></div></td>
									   
									</tr>
									<?php 
								$n++;}
							?>
							 </tbody>
                  </table>	
				  
				  <?php echo "**"; ?>
				  
				  <table class="table m-0 tableFixHead" id="rejected_sales">
                    <thead>
                    <tr>
                     <th>Sr.No.</th>
                      <th>Vendor Name</th>
                      <th>Campaign Code</th>
					  <th>Type Of Vendor</th> 
                      <th>Invoice Value</th> 					  
					   <th>Status</th>
					   <th>Comments</th>
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from payment_request where Director_status='Rejected' or ceo_status='Rejected'";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $x = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $x; ?></div></td>	
									  
									 <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["vendor_name"]; ?></div></td>
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["campaign_code"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["type_of_vendor"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["invoice"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Status"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["comments"]; ?></div></td>
									   
									
									</tr>
									<?php 
								$x++;}
							?>
							 </tbody>
                  </table>		
				  
	
				  
 <!-- ################################# PO ############################################ -->
	<?php echo "**"; ?>
	
	<table class="table m-0 tableFixHead" id="completed_PO_data">
                    <thead>
                    <tr>
                     <th>Sr.No.</th>
                          <th>
                            Campaign Code
                          </th>
                          <th>
                            Vendor Name
                          </th>
					       <th>
                            Total Of Vendor
                          </th>
                          <th>
                            Total Prints
                          </th>
                        <th>
                            Cost Per Unit
                          </th>	
                          <th>
                            Status
                          </th>							  
					   
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from purchase where status='Received'";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $c = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $c; ?></div></td>	
									  
									 <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["campaign_code"]; ?></div></td>
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["vendor_name"]; ?></div></td>
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Type_of_Vendor"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["total_prints"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["per_unitcost"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["status"]; ?></div></td>
									   
									  
									</tr>
									<?php 
								$c++;}
							?>
							 </tbody>
                  </table>		
				  
				  <?php echo "**"; ?>
	
	<table class="table m-0 tableFixHead" id="pending_PO_data">
                    <thead>
                    <tr>
                     <th>Sr.No.</th>
                          <th>
                            Campaign Code
                          </th>
                          <th>
                            Vendor Name
                          </th>
					       <th>
                            Total Of Vendor
                          </th>
                          <th>
                            Total Prints
                          </th>
                        <th>
                            Cost Per Unit
                          </th>	
                          <th>
                            Status
                          </th>					  
					   
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from purchase where status='Requested'";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $d = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $d; ?></div></td>	
									  
									 <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["campaign_code"]; ?></div></td>
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["vendor_name"]; ?></div></td>
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Type_of_Vendor"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["total_prints"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["per_unitcost"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["status"]; ?></div></td>
									   
									  
									</tr>
									<?php 
								$d++;}
							?>
							 </tbody>
                  </table>		
 <?php
}
 ?>