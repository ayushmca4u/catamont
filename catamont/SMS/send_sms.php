<?php
require 'vendor/autoload.php';
$sdk = new Aws\Sns\SnsClient([
    'region'  => 'us-east-1',
    'version' => 'latest',
    'credentials' => ['key' => 'AKIAITWUC6XYMBIMHJIA', 'secret' => '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']
  ]);
/*$result = $sdk->publish([
    'Message' => 'This is a test message.',
    'PhoneNumber' => '+919920782681',
    'MessageAttributes' => ['AWS.SNS.SMS.SenderID' => [
         'DataType' => 'String',
         'StringValue' => 'Mont'
      ]
  ]]);
*/
$args = array(
    "SenderID" => "CATAMONT",
    "SMSType" => "Transactional",
    "Message" => "Hello World! Visit www.tiagogouvea.com.br! CATAMONT",
    "PhoneNumber" => "+919867168516"
);

$result = $sdk->publish($args);
 $result = $result->toArray();
//$result=object($result);
echo "<pre>";
//var_dump($result);
//print_r($result);
echo $MessageId=$result['MessageId'];
        echo $x_amzn_requestid=$result['@metadata']['headers']['x-amzn-requestid'];
echo "</pre>";

?>
