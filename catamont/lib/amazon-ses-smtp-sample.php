<?php
require  "vendor/autoload.php";
$mail = new PHPMailer\PHPMailer\PHPMailer();;
$mail->isSMTP();
$mail->SMTPDebug = 2;
#$mail->Username = 'AKIAI2IYQA4575NTY4PQ';
#$mail->Password = 'AhxXqkcPSnRsTklY4jdQ4Xem49eNEqSoPlSUkxU9XyS6';
$mail->Username = 'server20148';
$mail->Password = 'Ak56Hmq2ERf9s8Q7Nw';
$mail->SMTPAuth = true;
$mail->SMTPSecure = 'tls';
$mail->setFrom('giftcards@catamont.com', 'Samriddhi Distributors');
$mail->clearAllRecipients();
$mail->clearAttachments();
$mail->clearCustomHeaders();

#$mail->addAddress('giftcards@catamont.com', 'Abhishek Agrawal');
$mail->addAddress('support@catamont.com', 'Support');
$mail->addAddress('ayush.mca@gmail.com', 'Support');
$mail->addAddress('ayush_mca4u@yahoo.co.in', 'Support');
$mail->addReplyTo('giftcards@catamont.com', 'Support');
//$headers = 'MIME-Version: 1.0' . "\r\n";
$headers="";
$headers .= "Return-Path:giftcards@catamont.com\r\n";
//$mail->AddCustomHeader($headers);

//$mail->AddCustomHeader('X-SES-CONFIGURATION-SET', 'cata_send_mail');
#$mail->Host = 'email-smtp.us-west-2.amazonaws.com';
//$mail->Host = 'email-smtp.us-east-1.amazonaws.com';
$mail->Host = 'smtp.socketlabs.com';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
#$mail->Host = 'email-smtp.eu-west-1.amazonaws.com';
$mail->Subject = 'SMTP sent by Sockt Labs';
/*$mail->Body = '<h1>Email Test</h1>
    <p>This email was sent through the
    <a href="https://aws.amazon.com/ses">Amazon SES</a> SMTP
    interface using the <a href="https://github.com/PHPMailer/PHPMailer">
    PHPMailer</a> class.</p>';
*/
//$mail->Port = 587;
$mail->Port = 25;
$mail->isHTML(true);
/*$mail->AltBody = "Email Test\r\nThis email was sent through the
    Amazon SES SMTP interface using the PHPMailer class.";*/

if(!$mail->send()) {
    echo "Email not sent. " , $mail->ErrorInfo , PHP_EOL;
} else {
    echo "Email sent!" , PHP_EOL;
}
?>
