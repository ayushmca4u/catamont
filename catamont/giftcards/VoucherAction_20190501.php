<?php
class VoucherAction
{
	function __construct()
        {

        }
	public  function fetch_voucher_count($request)
	{
		//echo  "<pre>";
		//print_r($_SERVER);
		$SERVER_IP=$_SERVER["REMOTE_ADDR"];
		//$SERVER_IP="182.77.81.150";
        	if($SERVER_IP!="182.77.81.150")
	        {
        	        echo  "Authorisation Failed, Do not have permission to access this page";
	                die;
        	}
		$cid=trim($request->getParameter("cid"));
                $cost=trim($request->getParameter("cost"));
                $status=trim($request->getParameter("status"));
                $vcode=trim($request->getParameter("vcode"));
		$where_clause="";
		if($cid)
			$where_clause.=" and client_id=$cid ";
		if($cost)
		{
			$cost="'".$cost."'";
                        $where_clause.=" and voucher_price=$cost ";
		}
		if($status)
		{
			$status="'".$status."'";
                        $where_clause.=" and status=$status ";
		}
		if($vcode)
		{
			$vcode="'".$vcode."'";
                        $where_clause.=" and voucher_code=$vcode ";
		}
		
		if(strlen($where_clause)==0)
		{
			echo "Please provide manadatory parameter  cid,cost,status,vocde";
			die;			
		}

		include "/home/httpd/htdocs/catamont/include/config.php";
                include "/home/httpd/htdocs/catamont/include/functions.php";
                include "/home/httpd/htdocs/catamont/lib/mysqli_conn.cls.php";
                include "/home/httpd/htdocs/catamont/lib/createlog.cls.php";

                $LOG_FILE_HANDLER=new Createlog('DEBUG',"generate_count_report");
                $LOG_FILE_HANDLER->logging('DEBUG',"SERVER REQUEST ".print_r($_SERVER,true));
                $LOG_FILE_HANDLER->logging('DEBUG',"START generate_count_report ");
                $mysqlObj=new mysqlDatabase(DB_USERNAME,DB_PASSWORD,DATABASE,SERVERIP,$LOG_FILE_HANDLER);
                $date = date("Y-m-d H:i:s");
                $icici_select_query="select count(1) as CNT from voucher_details where 1=1 $where_clause";
                $icici_voucher_detailsArr=$mysqlObj->getarray($icici_select_query);
		$count=$icici_voucher_detailsArr[0]['CNT'];
		echo "Total count as per filter by $cid $cost $status and $vode  ::  $count </br>";	
		echo "Total count  : $count";
		die;
	}
	public  function fetch_voucher_details($request)
        {
		$SERVER_IP=$_SERVER["REMOTE_ADDR"];
        //      $SERVER_IP="182.77.90.75";
                if($SERVER_IP!="42.106.195.51")
                {
                        echo  "Authorisation Failed, Do not have permission to access this page";
                        die;
                }
		$client_id=trim($request->getParameter("cid"));
                $vcode_price=trim($request->getParameter("cost"));
                $voucher_statuss=trim($request->getParameter("status"));
                $voucher_code=trim($request->getParameter("voucher_code"));
                $email=trim($request->getParameter("email"));
                $mobileno=trim($request->getParameter("mobileno"));
                $client_voucher_code=trim($request->getParameter("client_voucher_code"));
                $where_clause="";
                if($client_id)
                        $where_clause.=" and client_id=$client_id ";
                if($vcode_price)
                {
                        $vcode_price="'".$vcode_price."'";
                        $where_clause.=" and vcode_price=$vcode_price ";
                }
		if($client_voucher_code)
                {
                        $client_voucher_code="'".$client_voucher_code."'";
                        $where_clause.=" and client_voucher_code=$client_voucher_code ";
                }
                if($voucher_statuss)
                {
                        $voucher_statuss="'".$voucher_statuss."'";
                        $where_clause.=" and voucher_status=$voucher_statuss ";
                }
                if($voucher_code)
                {
                        $voucher_code="'".$voucher_code."'";
                        $voucher_code.=" and voucher_code=$voucher_code ";
                }
		if($email)
                {
                        $email="'".$email."'";
                        $where_clause.=" and email=$email ";
                }
		if($mobileno)
                {
                        $mobileno="'".$mobileno."'";
                        $where_clause.=" and mobileno=$mobileno ";
                }

                if(strlen($where_clause)==0)
                {
                        echo "Please provide manadatory parameter  cid,cost,status,vocde";
                        die;
                }
		include "/home/httpd/htdocs/catamont/include/config.php";
                include "/home/httpd/htdocs/catamont/include/functions.php";
                include "/home/httpd/htdocs/catamont/lib/mysqli_conn.cls.php";
                include "/home/httpd/htdocs/catamont/lib/createlog.cls.php";

                $LOG_FILE_HANDLER=new Createlog('DEBUG',"voucher_details_report");
                $LOG_FILE_HANDLER->logging('DEBUG',"START voucher_details_report ");
                $mysqlObj=new mysqlDatabase(DB_USERNAME,DB_PASSWORD,DATABASE,SERVERIP,$LOG_FILE_HANDLER);
                $date = date("Y-m-d H:i:s");
                $icici_select_query="select * from icici_voucher_details where 1=1 $where_clause  order by  updated_at desc limit 100";
                $icici_voucher_detailsArr=$mysqlObj->getarray($icici_select_query);
		if(!$icici_voucher_detailsArr[0]['vid'])
                {
                        $LOG_FILE_HANDLER->logging('ERROR',"Voucher Code not valid  : $icici_select_query");
			echo "Record not Found , please contact admin";
			die;
                }
		$table_str="";
		$table_str.="<table border=1>";
		$i=0;
		foreach($icici_voucher_detailsArr as $key=>$details)
		{
			$flip_details=array_flip($details);
			if($i==0)
			{
				$table_str.="<tr>";
				foreach($flip_details as $column_val=>$column_key)
				{
					$table_str.="<th>$column_key</th>";
				}
				$table_str.="</tr>";
				$i++;
			}
			$table_str.="<tr>";
			foreach($details as $key=>$val)
                                {
                                        $table_str.="<td>$val</td>";
                                }
			$table_str.="</tr>";
				
		}	
		echo  $table_str;
                die;

        }
	public function generate_voucher($request)
	{
		$vcode=$request->getParameter("vcode");
		$byemail=$request->getParameter("byemail");
		$bysms=$request->getParameter("bysms");
		$mobileno=$request->getParameter("mobileno");
		$email =$request->getParameter("email");				
		$vcode=trim($vcode);
		$mobileno=trim($mobileno);
		$email=trim($email);
		//error_reporting(E_ALL);
		//ini_set("display_errors",true);
		include "/home/httpd/htdocs/catamont/include/config.php";
		include "/home/httpd/htdocs/catamont/include/functions.php";
		include "/home/httpd/htdocs/catamont/lib/mysqli_conn.cls.php";
		include "/home/httpd/htdocs/catamont/lib/createlog.cls.php";

		$LOG_FILE_HANDLER=new Createlog('DEBUG',"generate_voucher");
		$LOG_FILE_HANDLER->logging('DEBUG',"START generation php ");
		$mysqlObj=new mysqlDatabase(DB_USERNAME,DB_PASSWORD,DATABASE,SERVERIP,$LOG_FILE_HANDLER);
       		$date = date("Y-m-d H:i:s");
        	$icici_select_query="select * from icici_voucher_details where voucher_code='$vcode' and voucher_expiry_date >'$date'";
	        $icici_voucher_detailsArr=$mysqlObj->getarray($icici_select_query);
        	if(!$icici_voucher_detailsArr[0]['vid'])
	        {
                	$LOG_FILE_HANDLER->logging('ERROR',"Voucher Code not valid  : $icici_select_query");
			return array("status"=>false,"errorMessage"=>"Voucher Code is not valid");
        	}
		$vid=$icici_voucher_detailsArr[0]['vid'];
		$voucher_status=$icici_voucher_detailsArr[0]['voucher_status'];
		$dbemail=$icici_voucher_detailsArr[0]['email'];
		$dbmobileno=$icici_voucher_detailsArr[0]['mobileno'];
		$icici_vcode_price=$icici_voucher_detailsArr[0]['vcode_price'];
		$icici_vcode_price="'".$icici_vcode_price."'";
		if($voucher_status=="assigned")
		{
			$LOG_FILE_HANDLER->logging('ERROR',"Flipkart Gifts Voucher Code with PIN Details already sent on your registered Email id or Mobile Number : $icici_select_query");
                        return array("status"=>false,"errorMessage"=>"<strong>Voucher Code already Activated</strong></br>Flipkart Gift Card Details was already sent on the Email ID entered by you.</br>Flipkart Gift Card Details was already sent on the Mobile Number entered by you.");
		}
		$select_query="select * from voucher_details where client_id=1 and status='created' and voucher_price=$icici_vcode_price limit 1";
                $voucher_detailsArr=$mysqlObj->getarray($select_query);
                if(!$voucher_detailsArr[0]['voucher_id'])
                {
                        $LOG_FILE_HANDLER->logging('ERROR',"Voucher Code not valid  : $icici_select_query");
                        return array("status"=>false,"errorMessage"=>"Voucher Code is not valid");
                }
		$voucher_detailsArr=$voucher_detailsArr[0];	
		$voucher_id=$voucher_detailsArr['voucher_id'];
		$flipcart_voucher_code=$voucher_detailsArr['voucher_code'];
		$flipcart_voucher_pin=$voucher_detailsArr['voucher_pin'];
		$flipcart_voucher_price=$voucher_detailsArr['voucher_price'];
		$flipcart_expiry_date=$voucher_detailsArr['expiry_date'];
		$flipcart_purchase_date=$voucher_detailsArr['created_at'];
		$flipcart_expiry_date=date("jS F Y",strtotime($flipcart_expiry_date));
		$flipcart_purchase_date=date("h:i A jS F, Y",strtotime($flipcart_purchase_date));
		$email_sent=false;
		$sms_sent=false;
		$voucher_update_str="";
		$icici_voucher_update_str="";
		if($email && $byemail=="on" && $voucher_status=="created")
		{
			// Send An email		
			$email_template="";
			$email_template.="<div>Dear ICICI Customer, </br>
					<p>You have received a Flipkart Gift Cards of Rs $flipcart_voucher_price valid up to $flipcart_expiry_date</p></div>";	
			$email_template.="<table width='70%'>";
			$email_template.="<tr><td><img height='100' width='800' src='http://www.catamont.com/giftcards/flipkart.jpeg'/></td></tr>";
			$email_template.="<tr style='background-color:#A9A9A9;text-align:center'><td>GIFT CARD DETAILS</td></tr>";
			$email_template.="<tr><td>";
			$email_template.="<table border=1 width='100%'>";
			$email_template.="<tr><th width='40%'>ID</th><th width='30%'>PIN*</th><th width=30%'>Value</th></tr>";
			$email_template.="<tr><td width='40%' align='center'>$flipcart_voucher_code</td><td width='30%' align='center'>$flipcart_voucher_pin</td><td width='30%' align='center'>$flipcart_voucher_price</td></tr>";
			$email_template.="</table>";
			$email_template.="</td></tr>";
			$email_template.="<tr style='background-color:#A9A9A9;align=center'><td>&nbsp;</td></tr>";
			$email_template.="<tr><td>*Pins generated at $flipcart_purchase_date</td></tr>";
			$email_template.="<tr><td style='text-align:center'><img  style='height:60px;width:100px;' src='http://www.catamont.com/giftcards/issued_by_flipkart.jpeg'/></td></tr>";
			$email_template.="<tr><td>&nbsp;</td></tr>";
			$email_template.="<tr><td><strong>Note: Please use the latest generated gift card pins for placing an order.</strong>
</td></tr>";
			$email_template.="<tr><td>&nbsp;</td></tr>";
			$email_template.="<tr><td><strong>How do you check balance in the Gift Card ?</strong></td></tr>";
			$email_template.="<tr><td>- Enter the Gift Card Number and PIN and check available balance in your Gift Card</td></tr>";
			$email_template.="<tr><td>&nbsp;</td></tr>";
			$email_template.="<tr><td><strong>How do you use a Gift Card?</strong></td></tr>";
			$email_template.="<tr><td>- Gift Cards are used to pay for purchases made on www.flipkart.com.";
			$email_template.="<tr><td>- You can add the Gift Card to your Flipkart Wallet by clicking on Add to Wallet button above.";
			$email_template.="<tr><td>- You can also redeem Gift Cards which are not added to your wallet by choosing the 'Gift Card' Payment Options during checkout.</td></tr>";
			$email_template.="<tr><td>&nbsp;</td></tr>";
			$email_template.="<tr><td><strong>What is my Flipkart Wallet?</strong></td></tr>";
			$email_template.="<tr><td>- Flipkart wallet is a repository for all your Gift Cards, Saved Cards and PhonePe Wallet.";
			$email_template.="<tr><td>- Flipkart Wallet balance is a sum of all your PhonePe wallet balance and Gift Card balances."; 
			$email_template.="<tr><td>Flipkart Wallet balance can be used seamlessly during payment in one simple click.";
			$email_template.="<tr><td>- You can manage, check current balance and transfer the Gift Card(s) to a friend from your Flipkart Wallet.</td></tr>";
			$email_template.="<tr><td>We request you to keep this email for future reference.";
			$email_template.="<tr><td>For your security, please do not share Gift Card details including PIN with anyone.";
			$email_template.="<tr><td>You are not required to share PIN details with Flipkart Customer care at any time.</td></tr>";
			$email_template.="<tr><td>&nbsp;</td></tr>";
			$email_template.="<tr><td>Need to get in Touch? Contact Us</td></tr>";
			$email_template.="<tr><td>&nbsp;</td></tr>";
			$email_template.="<tr><td><strong>Happy Shopping!</strong></td></tr>";
			$email_template.="<tr><td>Team Flipkart</td></tr>";
			$email_template.="<tr><td>&nbsp;</td></tr>";
			$email_template.="<tr><td>
<strong>Terms and conditions</strong></br>
<ol>
<li>Flipkart.com Gift Cards are issued by QwikCilver Solutions. QwikCilver is a private limited company incorporated under the laws of India, and is the issuer of Gift Cards.</li>
<li>The Gift Cards can be redeemed online against Sellers listed on www.flipkart.com only.</li>
<li>Gift Cards can be redeemed by selecting the payment mode as Gift Card. They can NOT be used to purchase Flipkart First subscriptions.</li>
<li>Gift Cards cannot be used to purchase other Gift Cards. Gift Cards cannot be reloaded or resold.</li>
<li>If the order value exceeds the Gift Card amount, the balance must be paid by Credit Card/Debit Card/Internet Banking.Cash on Delivery payment option cannot be used to pay the balance amount.</li>
<li>If the order value is less than the amount of the Gift Card, the outstanding balance (after deduction of order value) will reflect under the same Gift Card.</li>
<li>Gift Cards and their corresponding unused balance will expire 12 months from the date of issue.</li>
<li>Gift Cards cannot be redeemed for Cash or Credit, but are transferable.</li>
<li>Flipkart.com/QwikCilver Solutions are not responsible if Gift Card is lost, stolen or used without permission.</li>
<li>You can combine a maximum of 15 Gift Cards in a single order at the time of checkout. In case you wish to redeem more number of Gift Cards on a single order, please add your Gift Cards to the Wallet.</li>
<li>Flipkart.com/QwikCilver Solutions assumes no responsibility for the products purchased using the Gift Cards and any liability thereof is expressly disclaimed.</li>
<li>Validity of Gift Cards cannot be extended, new Gift Cards cannot be provided against the expired/unused Gift Cards.</li>
<li>In the event the beneficiary/Know Your Customer (KYC) details are found to be incorrect/ insufficient, QwikCilver Solutions/Flipkart.com retains the right to cancel the Gift Card issued.</li></ol></td></tr>";
			$email_template.="</table>";
			$EMAIL_RESPONSEARR=SendMail(array('email'=>$email,"email_template"=>$email_template));	
			if($EMAIL_RESPONSEARR['status']==false)
                        {
                                return array("status"=>false,"errorMessage"=>"EMAIL couldnot send please try again after some time.");
                        }
			$email_sent=true;
		}
		if($mobileno && $bysms=="on")
		{
			$SMS_TEMPLATE=str_replace("##voucher_code##","$flipcart_voucher_code",SMS_TEMPLATE);	
			$SMS_TEMPLATE=str_replace("##voucher_pin##","$flipcart_voucher_pin",$SMS_TEMPLATE);
			$SMS_TEMPLATE=str_replace("##voucher_price##","$flipcart_voucher_price",$SMS_TEMPLATE);
			$SMS_TEMPLATE=str_replace("##expiry_date##","$flipcart_expiry_date",$SMS_TEMPLATE);	
			$SMS_RESPONSEARR=SendSms(array('mobileno'=>$mobileno,"sms_template"=>$SMS_TEMPLATE));
			if($SMS_RESPONSEARR['status']==false)
			{
				return array("status"=>false,"errorMessage"=>"SMS couldnot send please try again after some time.");
			}
			$MessageId=$SMS_RESPONSEARR['MessageId'];
			$x_amzn_requestid=$SMS_RESPONSEARR['x_amzn_requestid'];	
			$sms_sent=true;
			// Send SMS
		}
		if($email_sent==true)	
		{
			$voucher_update_str.="status='assigned',updated_at=now()";
			$icici_voucher_update_str.="voucher_status='assigned',client_voucher_code='$flipcart_voucher_code',updated_at=now(),voucher_assigned_date=now(),email='$email'";
		}
		if($sms_sent==true)
		{
			if($email_sent==true)
				$icici_voucher_update_str.=",updated_at=now(),mobileno='$mobileno',MessageId='$MessageId',x_amzn_requestid='$x_amzn_requestid'";	
			else
				$icici_voucher_update_str.=" updated_at=now(),mobileno='$mobileno',MessageId='$MessageId',x_amzn_requestid='$x_amzn_requestid'";	
		}	
		$mysqlObj->begin_transaction();
		$commit=false;
		if(strlen($voucher_update_str)>0)
		{
			$voucher_update_query="update voucher_details set $voucher_update_str where voucher_id=$voucher_id";
		        $voucher_update_response=$mysqlObj->getresource($voucher_update_query);
	        	if(!$voucher_update_response)
	        	{
        	        	$mysqlObj->rollback_transaction();
	        	        $LOG_FILE_HANDLER->logging('ERROR',"INSERTION FAILED : $voucher_update_query");
				return array("status"=>false,"errorMessage"=>"Request could not complete.please try again after some time.");
	        	}
			$commit=true;
		}
		if(strlen($icici_voucher_update_str)>0)
                {
                        $icici_voucher_update_query="update icici_voucher_details set $icici_voucher_update_str where vid=$vid";
                        $icici_voucher_update_response=$mysqlObj->getresource($icici_voucher_update_query);
                        if(!$icici_voucher_update_response)
                        {
                                $mysqlObj->rollback_transaction();
                                $LOG_FILE_HANDLER->logging('ERROR',"INSERTION FAILED : $icici_voucher_update_query");
                                return array("status"=>false,"errorMessage"=>"Request could not complete.please try again after some time.");
                        }
			$commit=true;
                }
		if($commit==true)
			$mysqlObj->commit_transaction();
		else
			$mysqlObj->rollback_transaction();
		return array("status"=>true,"email_sent"=>$email_sent,"sms_sent"=>$sms_sent);
	}
}
?>
