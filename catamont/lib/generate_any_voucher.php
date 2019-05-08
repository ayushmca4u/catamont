<?php
error_reporting(0);
include "/home/httpd/htdocs/catamont/include/config.php";
include "/home/httpd/htdocs/catamont/lib/mysqli_conn.cls.php";
include "/home/httpd/htdocs/catamont/lib/createlog.cls.php";

$LOG_FILE_HANDLER=new Createlog('DEBUG',"generate_voucher");
$LOG_FILE_HANDLER->logging('DEBUG',"START generation php ");
$mysqlObj=new mysqlDatabase(DB_USERNAME,DB_PASSWORD,DATABASE,SERVERIP,$LOG_FILE_HANDLER);
//$gen_count=6999;
$gen_count=95;
for ($j = 0; $j <$gen_count ; $j++)
{
	$res = "";
	$res=substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 16);
	$res=strtoupper($res);
	$res="'".$res."'";
	$mysqlObj->begin_transaction();
	$date = date("Y-m-d H:i:s");
	//$newdate = strtotime ( '+365 day' , strtotime ( $date ) ) ;
	//$voucher_expiry_date = date ( 'Y-m-d H:i:s' , $newdate );
	$newdate="2020-08-27 23:59:59";	
	$voucher_expiry_date="'".$newdate."'";
	$vcode_price="200";
	$vcode_price="'".$vcode_price."'";
	$client_id=2;		
	$icici_insert_query="insert into icici_voucher_details(vid,client_id,voucher_code,vcode_price,voucher_status,voucher_expiry_date,voucher_created_date,created_at,updated_at) values(null,$client_id,$res,$vcode_price,'created',$voucher_expiry_date,now(),now(),now())";
        $icici_insert_response=$mysqlObj->getresource($icici_insert_query);
        if(!$icici_insert_query)
        {
                $mysqlObj->rollback_transaction();
                $LOG_FILE_HANDLER->logging('ERROR',"INSERTION FAILED : $insert_query");
                continue;
        }	
	echo "insert_query===$icici_insert_query \n";
	$mysqlObj->commit_transaction();
}
?>
