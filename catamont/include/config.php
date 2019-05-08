<?php
define("DB_USERNAME","catamontusr");
define("DB_PASSWORD","Sud@#123@mont");
define("DATABASE","catamont");
define("SERVERIP","127.0.0.1");
define("LOGINCLUDEPATH","/var/log/");
define("SMTP_USERNAME","server20148");
define("SMTP_PASSWORD","Ak56Hmq2ERf9s8Q7Nw");
define("SMTP_HOST","smtp.socketlabs.com");
define("SMTP_PORT","25");
define("SEND_SMS_KEY","AKIAITWUC6XYMBIMHJIA");
define("SEND_SMS_SECRET","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");
//$SMS_TEMPLATE="You have received a Flipkart Gift Card from ICICI BANK.Gift Card ID ##voucher_code## PIN*##voucher_pin## & Value Rs. ##voucher_price##. valid upto 10th August, 2019.";
define("SMS_TEMPLATE","You have received a Flipkart Gift Card from ICICI BANK. Gift Card ID ##voucher_code## PIN*##voucher_pin## & Value Rs. ##voucher_price##. Valid upto ##expiry_date##.");
$CLIENT_SMS_TEMPLATE[1]="You have received a Flipkart Gift Card from ICICI BANK. Gift Card ID ##voucher_code## PIN*##voucher_pin## & Value Rs. ##voucher_price##. Valid upto ##expiry_date##.";
$CLIENT_SMS_TEMPLATE[2]="You have received a OLA Gift Card from ICICI BANK. Gift Card ID ##voucher_code## PIN*##voucher_pin## & Value Rs. ##voucher_price##. Valid upto ##expiry_date##.";
?>
