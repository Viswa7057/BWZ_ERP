<?php
$loginUser = $_REQUEST['loginUser'];
$fromDate = $_REQUEST['outfromdate'];
$toDate = $_REQUEST['outtodate'];
require_once("db_connect.php");
$date = date('Y-m-d');
if($loginUser != ''){

//Total Completed sales
 $stmt_Totaluser="SELECT count(id) as total_count from installation_info where salesPerson_name='$loginUser' AND status='Completed' AND created_date>='$fromDate 00:00:00' AND created_date<='$toDate 23:59:59';";
	                           $rslt_Totaluser= mysqli_query($conn,$stmt_Totaluser);
	                           $row_Totaluser= mysqli_fetch_row($rslt_Totaluser);
							   $Total_sales = $row_Totaluser[0];
							   
//Total New sales
 $stmt_NEW_sale="SELECT count(id) as new_count from installation_info where salesPerson_name='$loginUser' AND RM_status='' AND status='NEW' AND created_date>='$fromDate 00:00:00' AND created_date<='$toDate 23:59:59';";
	                           $rslt_NewSale= mysqli_query($conn,$stmt_NEW_sale);
	                           $row_NewSale= mysqli_fetch_row($rslt_NewSale);
							   $Total_NewSale = $row_NewSale[0];
							   
//Total Rejected sales
 $stmt_Reject_sale="SELECT count(id) as reject_count from installation_info where salesPerson_name='$loginUser' AND RM_status='Rejected' AND created_date>='$fromDate 00:00:00' AND created_date<='$toDate 23:59:59';";
	                           $rslt_RejectSale= mysqli_query($conn,$stmt_Reject_sale);
	                           $row_RejectSale= mysqli_fetch_row($rslt_RejectSale);
							   $Total_RejectSale = $row_RejectSale[0];
                               
							   echo $Total_sales;
							   echo "**".$Total_NewSale;
							   echo "**".$Total_RejectSale;
							   echo "**";
							   
							   ?>

 <style>
.dt-head-center {text-align: center;}
.modal-content {
width: 138%;
}
</style>			
			 <table class="table m-0 tableFixHead" id="completed_sales">
                    <thead>
                    <tr>
                     <th>Sr.No.</th>
                      <th>Customer Name</th>
                      <th>Location</th>
					  <th>Product Type</th> 					  
					   <th>Buy Type</th> 
					   <th>Installation Status</th>
					   <th>Status</th>
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from installation_info where salesPerson_name='$loginUser' AND status='Completed' AND created_date>='$fromDate 00:00:00' AND created_date<='$toDate 23:59:59'";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $m = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $m; ?></div></td>								
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["customer_name"]; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["location"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["product"]; ?></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["buying_type"]; ?></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["status"]; ?></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["RM_status"]; ?></div></td>
									
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
                      <th>Customer Name</th>
                      <th>Location</th>
					  <th>Product Type</th> 					  
					   <th>Buy Type</th> 
					   <th>Installation Status</th>
					  
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from installation_info where salesPerson_name='$loginUser' AND RM_status='' AND status='NEW' AND created_date>='$fromDate 00:00:00' AND created_date<='$toDate 23:59:59'";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $n = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $m; ?></div></td>								
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["customer_name"]; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["location"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["product"]; ?></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["buying_type"]; ?></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["status"]; ?></div></td>
									   
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
                      <th>Customer Name</th>
                      <th>Location</th>
					  <th>Product Type</th> 					  
					   <th>Buy Type</th> 
					   <th>Installation Status</th>
					   <th>Status</th>
					   <th>Reason</th>
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from installation_info where salesPerson_name='$loginUser' AND RM_status='Rejected' AND created_date>='$fromDate 00:00:00' AND created_date<='$toDate 23:59:59'";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $x = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $m; ?></div></td>								
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["customer_name"]; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["location"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["product"]; ?></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["buying_type"]; ?></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["status"]; ?></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["RM_status"]; ?></div></td>
									 <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["comments"]; ?></div></td>
									
									
									</tr>
									<?php 
								$x++;}
							?>
							 </tbody>
                  </table>		
				  
				  
 <?php
}
 ?>