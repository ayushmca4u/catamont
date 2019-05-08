<?php
error_reporting(0);
include "/home/httpd/htdocs/catamont/include/config.php";
include "/home/httpd/htdocs/catamont/lib/mysqli_conn.cls.php";
include "/home/httpd/htdocs/catamont/lib/createlog.cls.php";

$LOG_FILE_HANDLER=new Createlog('DEBUG',"generate_voucher");
$LOG_FILE_HANDLER->logging('DEBUG',"START generation php ");
$mysqlObj=new mysqlDatabase(DB_USERNAME,DB_PASSWORD,DATABASE,SERVERIP,$LOG_FILE_HANDLER);
//$insert_query="select voucher_code,voucher_expiry_date,vcode_price from icici_voucher_details where client_id=1 and vcode_price=100 limit 16600";		
$insert_query="select voucher_code,voucher_expiry_date,vcode_price from icici_voucher_details where client_id=1 and vcode_price=2000 limit 600";		
$responseArr=$mysqlObj->getarray($insert_query);
foreach($responseArr as $key=>$details)
{
	$voucher_code=$details['voucher_code'];
	$voucher_expiry_date=$details['voucher_expiry_date'];
	$vcode_price=$details['vcode_price'];
	$voucher_code=trim($voucher_code);
	$voucher_expiry_date=trim($voucher_expiry_date);
	$vcode_price=trim($vcode_price);
	echo  "$voucher_code,$voucher_expiry_date,$vcode_price\n";
}
?>
