<?php
error_reporting(0);
include "/home/httpd/htdocs/catamont/include/config.php";
include "/home/httpd/htdocs/catamont/lib/mysqli_conn.cls.php";
include "/home/httpd/htdocs/catamont/lib/createlog.cls.php";

$LOG_FILE_HANDLER=new Createlog('DEBUG',"generate_voucher");
$LOG_FILE_HANDLER->logging('DEBUG',"START generation php ");
$mysqlObj=new mysqlDatabase(DB_USERNAME,DB_PASSWORD,DATABASE,SERVERIP,$LOG_FILE_HANDLER);
$responseArr=file("/home/httpd/htdocs/catamont/lib/flipkart_2000_voucher.csv");
foreach($responseArr as $key=>$details)
{
	$detailsArr=explode(",",$details);
	list($vcode,$pin,$price,$expiry_date)=$detailsArr;	
	$vcode=trim($vcode);
	$voucher_pin=trim($pin);	
	$price=trim($price);	
	$newdate="2020-04-29 23:59:59";
        $voucher_expiry_date="'".$newdate."'";
        $voucher_code="'".$vcode."'";
        $vcode_price="'".$price."'";
	$mysqlObj->begin_transaction();
	$voucher_pin="'".$voucher_pin."'";
	$insert_query="insert into voucher_details values(null,1,$voucher_code,$voucher_pin,$voucher_expiry_date,'created',$vcode_price,now(),now())";		
	#echo "insert_query =$insert_query \n";
	#continue;
	$insert_response=$mysqlObj->getresource($insert_query);
	if(!$insert_response)
	{
		$mysqlObj->rollback_transaction();
		$LOG_FILE_HANDLER->logging('ERROR',"INSERTION FAILED : $insert_query");	
		//continue;	
	}
	echo "insert_query===$insert_query \n";
	$mysqlObj->commit_transaction();
}
?>
