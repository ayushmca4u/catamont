<?php
error_reporting(0);
include "/home/httpd/htdocs/catamont/include/config.php";
include "/home/httpd/htdocs/catamont/include/functions.php";
include "/home/httpd/htdocs/catamont/lib/mysqli_conn.cls.php";
include "/home/httpd/htdocs/catamont/lib/createlog.cls.php";

$LOG_FILE_HANDLER=new Createlog('DEBUG',"send_alert");
$LOG_FILE_HANDLER->logging('DEBUG',"START send_alert script ");
$mysqlObj=new mysqlDatabase(DB_USERNAME,DB_PASSWORD,DATABASE,SERVERIP,$LOG_FILE_HANDLER);
	$icici_select_query="select count(1) as CNT,client_id,vcode_price from icici_voucher_details group by client_id,vcode_price ";
	$icici_voucher_detailsArr=$mysqlObj->getarray($icici_select_query);
	$total_requested_users=count($icici_voucher_detailsArr);
	if($total_requested_users==0)
	{
		$LOG_FILE_HANDLER->logging('ERROR',"Users Details Not Found for the $client_id : $icici_select_query");
	}	
	$client_price_details=array();
	foreach($icici_voucher_detailsArr as $key=>$details)
	{
		$client_id=$details['client_id'];
		$vcode_price=$details['vcode_price'];
		$client_price_details[$client_id][]=$vcode_price;
	}
	foreach($client_price_details as $cid=>$cleint_details)		
	{
		foreach($cleint_details as $key2=>$p_details)
		{
			$select_query="select count(1) as CNT from voucher_details where client_id=$cid and status='created' and voucher_price=$p_details";
                	$card_count=$mysqlObj->getcount($select_query);
			if($card_count<=0)
			{
				$card_count=0;
			}	
			$alert_query="select * from send_alert_details where client_id=$cid and status='pending' and voucher_price=$p_details order by minimum_qty desc";
			$alert_query_detailsArr=$mysqlObj->getarray($alert_query);
			$total_alert_count=count($alert_query_detailsArr);
			if($total_requested_users==0)
		        {
                		$LOG_FILE_HANDLER->logging('ERROR',"Alert Not configured for $cid and $p_details : $alert_query");
		        }
			foreach($alert_query_detailsArr as $key1=>$alert_details)
			{
				$alert_id=$alert_details['alert_id'];
				$minimum_qty=$alert_details['minimum_qty'];
				$status=$alert_details['status'];
				if($card_count <=$minimum_qty && $status=="pending")
				{
					$SMS_TEMPLATE="check for $minimum_qty , Need to refill voucher of Rs $p_details remaining quantity : $total_alert_count for client $cid";
					//$mobileno="9820340255";
					$mobileno="9820340255";
					$SMS_RESPONSEARR=SendSms(array('mobileno'=>$mobileno,"sms_template"=>$SMS_TEMPLATE));
		                        if($SMS_RESPONSEARR['status']==false)
                		        {
                                		$LOG_FILE_HANDLER->logging('ERROR',"SMS ($mobileno) couldnot send for voucher_id=$vid.");
			                        //      return array("status"=>false,"errorMessage"=>"SMS couldnot send please try again after some time.");
                        		}			
					$mobileno="9920782681";
                                        $SMS_RESPONSEARR=SendSms(array('mobileno'=>$mobileno,"sms_template"=>$SMS_TEMPLATE));
					$mysqlObj->begin_transaction();
	                        	$voucher_update_query="update send_alert_details set status='sent',updated_at=now() where alert_id=$alert_id";
        		                $voucher_update_response=$mysqlObj->getresource($voucher_update_query);
                        		if(!$voucher_update_response)
		                        {
                		                $mysqlObj->rollback_transaction();
                                		$LOG_FILE_HANDLER->logging('ERROR'," Could Not update Database $voucher_update_query");
						continue;
		                        }
					$mysqlObj->commit_transaction();
		                }		
			}	
		}	
	}
	$LOG_FILE_HANDLER->logging('DEBUG',"END send_alert script");
?>
