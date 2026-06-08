<?php
$loginUser = $_REQUEST['loginUser'];
require_once("db_connect.php");
$date = date('Y-m-d');
if($loginUser != ''){

//Total Completed sales
 $stmt_Totaluser="SELECT count(id) as total_count from order_details where ceo_status='Accepted';";
	                           $rslt_Totaluser= mysqli_query($conn,$stmt_Totaluser);
	                           $row_Totaluser= mysqli_fetch_row($rslt_Totaluser);
							   $Total_sales = $row_Totaluser[0];
							   
//Total New sales
 $stmt_NEW_sale="SELECT count(id) as new_count from order_details where HOD_Status='Accepted';";
	                           $rslt_NewSale= mysqli_query($conn,$stmt_NEW_sale);
	                           $row_NewSale= mysqli_fetch_row($rslt_NewSale);
							   $Total_NewSale = $row_NewSale[0];
							   
//Total Rejected sales
 $stmt_Reject_sale="SELECT count(id) as reject_count from order_details where status='Rejected';";
	                           $rslt_RejectSale= mysqli_query($conn,$stmt_Reject_sale);
	                           $row_RejectSale= mysqli_fetch_row($rslt_RejectSale);
							   $Total_RejectSale = $row_RejectSale[0];

//Total Completed Operation
 $stmt_TotalOperation="SELECT count(id) as total_count from order_details where status='Completed'";
	                           $rslt_TotalOperation= mysqli_query($conn,$stmt_TotalOperation);
	                           $row_TotalOperation= mysqli_fetch_row($rslt_TotalOperation);
							   $Total_Operation = $row_TotalOperation[0];
//Total New Operation
 $stmt_NEW_Operation="SELECT count(id) as new_count from order_details where status='OPS'";
	                           $rslt_NewOperation= mysqli_query($conn,$stmt_NEW_Operation);
	                           $row_NewOperation= mysqli_fetch_row($rslt_NewOperation);
							   $Total_NewOperation = $row_NewOperation[0];
							   
//Total Rejected Operation
 $stmt_Reject_Operation="SELECT count(id) as reject_count from order_details where status='Rejected';";
	                           $rslt_RejectOperation= mysqli_query($conn,$stmt_Reject_Operation);
	                           $row_RejectOperation= mysqli_fetch_row($rslt_RejectOperation);
							   $Total_RejectOperation = $row_RejectOperation[0];

//Total Completed Finance
 $stmt_TotalFinance="SELECT count(id) as total_count from payment_request where Status='Payment Done';";
	                           $rslt_TotalFinance= mysqli_query($conn,$stmt_TotalFinance);
	                           $row_TotalFinance= mysqli_fetch_row($rslt_TotalFinance);
							   $Total_Finance = $row_TotalFinance[0];
							   
//Total New Finance
 $stmt_NEW_Finance="SELECT count(id) as new_count from payment_request where Status='Requested';";
	                           $rslt_NewFinance= mysqli_query($conn,$stmt_NEW_Finance);
	                           $row_NewFinance= mysqli_fetch_row($rslt_NewFinance);
							   $Total_NewFinance = $row_NewFinance[0];

//Total Rejected sales
 $stmt_Reject_Finance="SELECT count(id) as reject_count from payment_request where Director_status='Rejected';";
	                           $rslt_RejectFinance= mysqli_query($conn,$stmt_Reject_Finance);
	                           $row_RejectFinance= mysqli_fetch_row($rslt_RejectFinance);
							   $Total_RejectFinance = $row_RejectFinance[0];

//Total Completed INVOICE
 $stmt_Total_invoice="SELECT count(id) as total_count from order_details where ceo_status='Accepted' and customer_invoice !='';";
	                           $rslt_Total_invoice= mysqli_query($conn,$stmt_Total_invoice);
	                           $row_Total_invoice= mysqli_fetch_row($rslt_Total_invoice);
							   $Total_invoice = $row_Total_invoice[0];
							   
//Total New INVOICE
 $stmt_NEW_invoice="SELECT count(id) as new_count from order_details where ceo_status='Accepted' and customer_invoice='';";
	                           $rslt_New_invoice= mysqli_query($conn,$stmt_NEW_invoice);
	                           $row_New_invoice= mysqli_fetch_row($rslt_New_invoice);
							   $Total_New_invoice = $row_New_invoice[0];

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

							   echo "**".$Total_Finance;
							   echo "**".$Total_NewFinance;
							   echo "**".$Total_RejectFinance;
							   
							   echo "**".$Total_invoice;
							   echo "**".$Total_New_invoice;
							   
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
			 <table class="table m-0 tableFixHead" id="completed_sales">
                    <thead>
                    <tr>
                     <th>Sr.No.</th>
                      <th>Company Name</th>
                      <th>Campaing Details</th>
					  <th>Media Type</th> 	
					  	  <th>Sales Person</th>
					   <th>Total No of Vehicles</th> 
					   <th>Status</th>
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from order_details where ceo_status='Accepted'";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $m = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $m; ?></div></td>								
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["company_name"]; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Campaing_Details"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Media_Type"]; ?></div></td>
									    <td><div class="sparkbar" data-color="#00a65a" data-height="20"><a href="ops_details.php"><?php echo $row["sales_person"]; ?></a></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Total_No_of_Vehicles"]; ?></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["status"]; ?></div></td>
									   
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
                      <th>Company Name</th>
                      <th>Campaing Details</th>
					  <th>Media Type</th> 	
					  <th>Sales Person</th>
					   <th>Total No of Vehicles</th> 
					   <th>Status</th>
					  
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from order_details where HOD_Status='Accepted'";
						
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $n = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><a href="ops_details.php"><?php echo $m; ?></a></div></td>								
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><a href="ops_details.php"><?php echo $row["company_name"]; ?></a></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><a href="ops_details.php"><?php echo $row["Campaing_Details"]; ?></a></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><a href="ops_details.php"><?php echo $row["Media_Type"]; ?></a></div></td>
									      <td><div class="sparkbar" data-color="#00a65a" data-height="20"><a href="ops_details.php"><?php echo $row["sales_person"]; ?></a></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><a href="ops_details.php"><?php echo $row["Total_No_of_Vehicles"]; ?></a></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><a href="ops_details.php"><?php echo $row["status"]; ?></a></div></td>
									   
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
                      <th>Company Name</th>
                      <th>Campaing Details</th>
					  <th>Media Type</th> 	
					  	  <th>Sales Person</th>
					   <th>Total No of Vehicles</th> 
					   <th>Status</th>
					   <th>Comments</th>
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from order_details where status='Rejected'";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $x = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $m; ?></div></td>								
									 <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["company_name"]; ?></div></td>
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Campaing_Details"]; ?></div></td>
									 
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Media_Type"]; ?></div></td>
									    <td><div class="sparkbar" data-color="#00a65a" data-height="20"><a href="ops_details.php"><?php echo $row["sales_person"]; ?></a></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Total_No_of_Vehicles"]; ?></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["status"]; ?></div></td>
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["comments"]; ?></div></td>
									   
									
									</tr>
									<?php 
								$x++;}
							?>
							 </tbody>
                  </table>		
				  
				 
				    <?php echo "**"; ?>
				   
				   <table class="table m-0 tableFixHead" id="completed_finance">
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
				  
				  <table class="table m-0 tableFixHead" id="pending_finance">
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
				  
				  <table class="table m-0 tableFixHead" id="rejected_finance">
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

                        $e = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $e; ?></div></td>	
									  
									 <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["vendor_name"]; ?></div></td>
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["campaign_code"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["type_of_vendor"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["invoice"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Status"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["comments"]; ?></div></td>
									   
									
									</tr>
									<?php 
								$e++;}
							?>
							 </tbody>
                  </table>		
				  
<!-- ################################# INVOICE ############################################ -->
	<?php echo "**"; ?>
	
	<table class="table m-0 tableFixHead" id="completed_invoice_data">
                    <thead>
                    <tr>
                     <th>Sr.No.</th>
                          <th>
                            Campaign Code
                          </th>
                          <th>
                            Company Name
                          </th>
					       <th>
                            Total No.Of Vehicle
                          </th>
                          <th>
                            Total Value Without GST
                          </th>					  
					   
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from order_details where ceo_status='Accepted' and customer_invoice !=''";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $a = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $a; ?></div></td>	
									  
									 <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["code"]; ?></div></td>
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["company_name"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Total_No_of_Vehicles"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Total_Value_Without_GST"]; ?></div></td>
									   
									  
									</tr>
									<?php 
								$a++;}
							?>
							 </tbody>
                  </table>		
				  
				  <?php echo "**"; ?>
	
	<table class="table m-0 tableFixHead" id="pending_invoice_data">
                    <thead>
                    <tr>
                     <th>Sr.No.</th>
                          <th>
                            Campaign Code
                          </th>
                          <th>
                            Company Name
                          </th>
					       <th>
                            Total No.Of Vehicle
                          </th>
                          <th>
                            Total Value Without GST
                          </th>					  
					   
                    </tr>
                    </thead>
                    <tbody> 
					<?php 
						
										
						$stmt_select = "SELECT * from order_details where ceo_status='Accepted' and customer_invoice =''";
                        $rslt_rs = mysqli_query($conn, $stmt_select);

                        $b = 1;
                        while ($row = mysqli_fetch_assoc($rslt_rs)) {
							?>
					<tr>
							
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $b; ?></div></td>	
									  
									 <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["code"]; ?></div></td>
									  
									  <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["company_name"]; ?></div></td>
									 
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Total_No_of_Vehicles"]; ?></div></td>
									   
									   <td><div class="sparkbar" data-color="#00a65a" data-height="20"><?php echo $row["Total_Value_Without_GST"]; ?></div></td>
									   
									  
									</tr>
									<?php 
								$b++;}
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