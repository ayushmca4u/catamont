<?php
error_reporting(0);
include "/home/httpd/htdocs/catamont/include/config.php";
include "/home/httpd/htdocs/catamont/include/functions.php";
include "/home/httpd/htdocs/catamont/lib/mysqli_conn.cls.php";
include "/home/httpd/htdocs/catamont/lib/createlog.cls.php";

$LOG_FILE_HANDLER=new Createlog('DEBUG',"send_alert_requested_voucher");
$LOG_FILE_HANDLER->logging('DEBUG',"START send_alert_requested_voucher script");
$mysqlObj=new mysqlDatabase(DB_USERNAME,DB_PASSWORD,DATABASE,SERVERIP,$LOG_FILE_HANDLER);
$clinet_ids_Arr=array(1,2,3);
$date = date("Y-m-d H:i:s");
foreach($clinet_ids_Arr as $client_id_key=>$client_id)
{
	$icici_selectArr="";
	$card_count="";	
	//$icici_select_query="select count(1) as CNT,vcode_price from icici_voucher_details where client_id=$client_id and voucher_status='assigned' group by vcode_price";
	$icici_select_query="select count(1) as CNT,vcode_price from icici_voucher_details where client_id=$client_id and voucher_status='requested' group by vcode_price";
	$icici_selectArr=$mysqlObj->getarray($icici_select_query);
	$card_count=count($icici_selectArr);	
        if($card_count<=0)
        {
		$LOG_FILE_HANDLER->logging('ERROR',"Voucher Request Details Not Found for the $client_id : $icici_select_query");
		continue;
        }
	$voucher_count_details=array();
	foreach($icici_selectArr as $key=>$details)
	{
		$voucher_count_details[$details['vcode_price']]=$details['CNT'];
	}
	if(count($voucher_count_details)>0)
	{	
		foreach($voucher_count_details as $voucher_price=>$voucher_count)
		{
			$SMS_TEMPLATE=" Total $voucher_count Users  Requested for Voucher of Rs $voucher_price  of client $client_id, please refill the flipkart voucher for Rs $voucher_price";
			$mobileno="9820340255";
                	$SMS_RESPONSEARR=SendSms(array('mobileno'=>$mobileno,"sms_template"=>$SMS_TEMPLATE));
	                if($SMS_RESPONSEARR['status']==false)
        	        {
                	        $LOG_FILE_HANDLER->logging('ERROR',"SMS ($mobileno) couldnot send alert");
	                }
        	        $mobileno="9920782681";
                	$SMS_RESPONSEARR=SendSms(array('mobileno'=>$mobileno,"sms_template"=>$SMS_TEMPLATE));
		}
	}
}
$LOG_FILE_HANDLER->logging('DEBUG',"END : send_voucher_to_requested_users script");
?>
