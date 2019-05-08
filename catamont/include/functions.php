<?php
function SendMail($inputArr)
{
	if(!$inputArr['email'])
        {
                return  array("status"=>false,"errorMessage"=>"Missing  Manadatory Parameter Email Address");
        }
	if(!$inputArr['email_template'])
        {
                return array("status"=>false,"errorMessage"=>"Missing  Manadatory Parameter EMail Template");
        }
	$email=$inputArr['email'];
	$Body=$inputArr['email_template'];
	require_once "/home/httpd/htdocs/catamont/lib/vendor/autoload.php";
	require_once "/home/httpd/htdocs/catamont/include/config.php";	
	$mail = new PHPMailer\PHPMailer\PHPMailer();
	$mail->isSMTP();
	//$mail->SMTPDebug = 2;		
	$mail->Username = SMTP_USERNAME;
	$mail->Password = SMTP_PASSWORD;
	$mail->Host = SMTP_HOST;
	$mail->Port = SMTP_PORT;
	$mail->SMTPAuth = true;
	$mail->SMTPSecure = 'tls';
	$mail->clearAllRecipients();
	$mail->clearAttachments();
	$mail->clearCustomHeaders();
	$mail->setFrom('giftcards@catamont.com', 'Your Flipkart Gift Card');
	$mail->addAddress("$email","ICICI Bank Customer");	
	$mail->Subject = 'You have received a Flipkart Gift Card from ICICI BANK';
	$mail->Body    = $Body;
	$mail->isHTML(true);	
	$response=$mail->send();
	if($response)
	{
		return array("status"=>true,"errorMessage"=>"Email sent");
	}	
	return array("status"=>false,"errorMessage"=>"Email not sent");
}

function SendSms($inputArr)
{
	//error_reporting(E_ALL);
	//ini_set("display_errors",true);	
	if(!$inputArr['mobileno'])
	{
		 return array("status"=>false,"errorMessage"=>"Missing  Manadatory Parameter Mobile Number");
	}
	if(!$inputArr['sms_template'])
        {
                return array("status"=>false,"errorMessage"=>"Missing  Manadatory Parameter SMS Template");
        }
	require_once "/home/httpd/htdocs/catamont/SMS/vendor/autoload.php";
	require_once "/home/httpd/htdocs/catamont/include/config.php";
	$sdk = new Aws\Sns\SnsClient([
    		'region'  => 'us-east-1',
		'version' => 'latest',
		'credentials' => ['key' =>SEND_SMS_KEY, 'secret' => SEND_SMS_SECRET]
	]);
	$mobileno=$inputArr['mobileno'];
	$template_message=$inputArr['sms_template'];
	$mobileno="+91$mobileno";
	$args = array(
  		  "SenderID" => "CATAMONT",
		  "SMSType" => "Transactional",
		  "Message" => "$template_message",
    		  "PhoneNumber" => "$mobileno"
	);
	$result = $sdk->publish($args);
	$result = $result->toArray();
	$MessageId=$result['MessageId'];
	$x_amzn_requestid=$result['@metadata']['headers']['x-amzn-requestid'];
	if($MessageId)
	{
		return array("status"=>true,"MessageId"=>"$MessageId","x_amzn_requestid"=>"$x_amzn_requestid");
	}			
	else
	{
		return array("status"=>false,"errorMessage"=>"Could not send SMS");
	}	
}
?>
