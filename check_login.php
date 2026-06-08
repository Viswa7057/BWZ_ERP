<?php
session_start();

// Define an array of restricted pages
$restricted_pages = array("Accounts/acc_invoice_request.php", "Accounts/acc_payments_request.php","Accounts/acc_po_request.php","Accounts/accounts_dashboard.php","Accounts/accounts_details.php",
"Accounts/accounts_report.php","Accounts/accounts_vendor_details.php","Accounts/edit_installationInfo.php","Accounts/logout.php","Accounts/operations_data.php","Accounts/ops_date_filter.php",
"Accounts/ops_details.php","Accounts/payment_report.php","Accounts/po_report.php","Accounts/view_vendorInfo.php",
/////////////////////////////////////////////////////////////////////////

"Admin/admin_dashboard.php", "Admin/admin_sales_data.php","Admin/director_payment_request.php","Admin/edit_installationInfo.php","Admin/logout.php",
"Admin/ops_details.php","Admin/ops_vendor_details.php","Admin/payment_report.php","Admin/po_report.php","Admin/sales_data.php","Admin/sales_date_filter.php",
"Admin/sales_report.php","Admin/user.php","Admin/useredit_data.php","Admin/view_vendorInfo.php",
/////////////////////////////////////////////////////////////////////////////

"CEO/ceo_dashboard.php","CEO/ceo_dashboard_nitesh.php","CEO/ceo_payment_request.php","CEO/download_zip.php","CEO/edit_installationInfo.php",
"CEO/get_total_invoice.php","CEO/get_total_payment.php","CEO/get_total_revenue.php","CEO/logout.php","CEO/operations_data.php","CEO/ops_date_filter.php",
"CEO/ops_details.php","CEO/ops_report.php","CEO/ops_vendor_details.php","CEO/payment_report.php","CEO/po_report.php","CEO/sales_report.php",
"CEO/view_vendorInfo.php",

/////////////////////////////////////////////////////////////////////////////

"Graphics/download_zip.php","Graphics/edit_installationInfo.php","Graphics/graph_dashboard.php","Graphics/graph_report.php","Graphics/graph_vendor_details.php",
"Graphics/logout.php","Graphics/operations_data.php","Graphics/ops_date_filter.php","Graphics/payment_graph.php","Graphics/view_vendorInfo.php",
/////////////////////////////////////////////////////////////////////////////


"Operations/download_zip.php","Operations/edit_installationInfo.php","Operations/edit_po_request.php","Operations/fetch_vendor_details.php","Operations/logout.php",
"Operations/operations_data.php","Operations/ops_dashboard.php","Operations/ops_date_filter.php","Operations/ops_details.php","Operations/ops_vendor_details.php","Operations/payment_report.php",
"Operations/payments_request.php","Operations/payments_request1.php","Operations/payments_request_nitesh.php","Operations/po_report.php","Operations/po_request.php","Operations/view_vendorInfo.php",

/////////////////////////////////////////////////////////////////////////////


"Sales/edit_installationInfo.php","Sales/logout.php","Sales/order_details.php","Sales/sales_dashboard.php","Sales/sales_data.php",
"Sales/sales_date_filter.php","Sales/sales_report.php",

/////////////////////////////////////////////////////////////////////////////

"sales_hod/edit_installationInfo.php","sales_hod/hod_dashboard.php","sales_hod/logout.php","sales_hod/order_details.php","sales_hod/sales_data.php",
"sales_hod/sales_date_filter.php","sales_hod/sales_report.php",

/////////////////////////////////////////////////////////////////////////////


"db_connect.php","vendor_registration.php",
"thank_you.html","logout.php"/* Add more pages as needed */);

// Check if the user is not logged in and the requested page is restricted,
if (!isset($_SESSION['user_id']) && in_array($_SERVER['REQUEST_URI'], $restricted_pages)) {
    // Redirect to the login page
    header("Location: index.php");
    exit();
}
 