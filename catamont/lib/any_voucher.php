<?php
error_reporting(0);
include "/home/httpd/htdocs/catamont/include/config.php";
include "/home/httpd/htdocs/catamont/lib/mysqli_conn.cls.php";
include "/home/httpd/htdocs/catamont/lib/createlog.cls.php";

$LOG_FILE_HANDLER=new Createlog('DEBUG',"generate_voucher");
$LOG_FILE_HANDLER->logging('DEBUG',"START generation php ");
$mysqlObj=new mysqlDatabase(DB_USERNAME,DB_PASSWORD,DATABASE,SERVERIP,$LOG_FILE_HANDLER);
$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
for ($j = 0; $j < 2; $j++)
{
	$k=$j+1;
	$res = "";
	$res=substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 8);
	$res=strtoupper($res);
	$res="'".$res."'";
	$mysqlObj->begin_transaction();
	$voucher_pin="0000$k";	
	$voucher_pin="'".$voucher_pin."'";
	$expiry_date="2020-04-30 10:20:20";
	$expiry_date="'".$expiry_date."'";
	#$insert_query="insert into voucher_details values(null,2,$res,$voucher_pin,$expiry_date,'created','100.00',now(),now())";		
	$insert_query="insert into voucher_details values(null,1,$res,$voucher_pin,$expiry_date,'created','2000.00',now(),now())";		
	#echo  "insert_query===$insert_query \n";	
	#continue;
	$insert_response=$mysqlObj->getresource($insert_query);
	if(!$insert_response)
	{
		$mysqlObj->rollback_transaction();
		$LOG_FILE_HANDLER->logging('ERROR',"INSERTION FAILED : $insert_query");	
		continue;	
	}
	echo "insert_query===$insert_query \n";
	$mysqlObj->commit_transaction();
}
?>
