<?php
class object {
}
class Request
{
	function getParameter($name)
        {
                if($name == "app_lang")
                {
                        if($_GET[$name] != "")
                                return $_GET[$name];
                        elseif($_POST[$name] != "")
                                return $_POST[$name];
                        else
                                return $_COOKIE[$name];
                }
                if($name == "login" && $_COOKIE["Rl"])
                {
                        return $_COOKIE["Rl"];
                }
                if($name == "session_id" && $_COOKIE["Rsc"])
                {
                        return $_COOKIE["Rsc"];
                }
                if($name == "output")
                {
                        if($_GET[$name] != "")
                                return $_GET[$name];
                        elseif($_POST[$name] != "")
                                return $_POST[$name];
                        else
                                return base64_decode($_COOKIE[$name]);
                }
                return $this->prevent_xss($_REQUEST[$name]);
        }
	function prevent_xss_v1($str)
        {
                if (is_array($str)) {
                        foreach($str as $key => $value){
                                $str[$key] = $this->prevent_xss_v1($value);
                        }
                } else {
                        $str = preg_replace('!<script([^>]*)>!si', '&lt;script$1&gt;', $str);
                        $str = preg_replace('!<object([^>]*)>!si', '&lt;object$1&gt;', $str);
                        $str = str_replace('</script>', '&lt;/script&gt;', $str);
                        $str = preg_replace('!(\S+)script\s*:!si', '$1scriipt:', $str);
                        $str = preg_replace('!\bon[a-zA-Z]*\s*=!si', 'onHack=', $str);
                        $str = preg_replace('#alert\(#i', '', $str);
                        $str = preg_replace('#prompt\(#i', '', $str);
                        $str = preg_replace('#confirm\(#i', '', $str);
                        $str = preg_replace('#eval\(#i', '', $str);
                        $str = preg_replace('#document\.#i', '', $str);
                        $str = str_replace("%26lt%3Bscript",'',$str);
                        $str = str_replace("%26lt%3B%2Fscript%26gt",'',$str);
                }
                return $str;
        }
	function prevent_xss($str)
        {
                                 #var_dump($str);
                if (is_array($str)) {
                        foreach($str as $key => $value){
                                $str[$key] = $this->prevent_xss($value);
                        }
                } else {

                        $str = html_entity_decode($str, ENT_COMPAT, "UTF-8");


                        /*oneventhandlers*/
                        $str = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $str);
                        $str = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $str);
                        $str = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $str);
                        $str = preg_replace('#(<[^>]+[\s\r\n\"\'])(on|xmlns)[^>]*>#iU',"$1>",$str);

                        $str = preg_replace('#</*\w+:\w[^>]*>#i',"",$str);
                        do {
                                $oldstr = $str;
                                $str = preg_replace('#</*(applet|meta|xml|blink|link|style|script|em-bed|ob-ject|i-frame|frame|frameset|ilayer|layer|bgsound|title|base)[^>]*>#i',"",$str);
                         } while ($oldstr != $str);

                        $str = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $str);
                        $str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $str);
                        $str = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $str);

                        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $str);
                        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $str);
                        $str = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $str);
			$str = preg_replace('#alert\(#i', '', $str);
                        $str = preg_replace('#prompt\(#i', '', $str);
                        $str = preg_replace('#confirm\(#i', '', $str);
                        $str = preg_replace('#eval\(#i', '', $str);
                        $str = preg_replace('#document\.#i', '', $str);
                        $str = preg_replace('!<sc-ript([^>]*)(>)?!si', '&lt;script$1&gt;', $str);
                        $str = preg_replace('!<ob-ject([^>]*)(>)?!si', '&lt;object$1&gt;', $str);
                        $str = preg_replace('!<em-bed([^>]*)(>)?!si', '&lt;embed$1&gt;', $str);
                        $str = preg_replace('!<i-frame([^>]*)(>)?!si', '&lt;iframe$1&gt;', $str);
                        $str = str_ireplace('</script>', '&lt;/script&gt;', $str);
                        $str = preg_replace('!(\S+)script\s*:!si', '$1scriipt:', $str);
                        $str = preg_replace('!\bon[a-zA-Z]*\s*=!si', 'onHack=', $str);
                        $str = preg_replace('!(.*):[\s]*expression[\s]*\(!si', '$1:ex-pression', $str);
                        $str = preg_replace('!<meta([^>]*)>!si', '&lt;meta$1&gt;', $str);

                }
                return $str;
        }
}
$request        = new Request();
$REQ_PARAM      = new object();

if ($_SERVER['PATH_INFO'] == "" || $_SERVER['PATH_INFO'] == "/")
{
        //$REQ_PARAM->do = "login";
}
else
{
        $tmp_arr_path           = explode("/",$_SERVER['PATH_INFO']);
        $REQ_PARAM->do          = $request->prevent_xss($tmp_arr_path[1]);
        $REQ_PARAM->action      = $request->prevent_xss($tmp_arr_path[2]);
        $REQ_PARAM->id          = $request->prevent_xss($tmp_arr_path[3]);
        $REQ_PARAM->num         = $request->prevent_xss($tmp_arr_path[4]);
        $REQ_PARAM->num1        = $request->prevent_xss($tmp_arr_path[5]);
        $REQ_PARAM->num2        = $request->prevent_xss($tmp_arr_path[6]);
        $REQ_PARAM->num3        = $request->prevent_xss($tmp_arr_path[7]);
        $REQ_PARAM->num4        = $request->prevent_xss($tmp_arr_path[8]);

        $pagination  = $tmp_arr_path[count($tmp_arr_path)-1];
    $pagination_limit ='';
        if($pagination   ==  $REQ_PARAM->action)
        {
       $pagination_prev ="1-20";
       $pagination_next ="20-40";
        }
        else
        {
                $paginationex=@explode("-",$pagination);

                if(is_numeric($paginationex[1]))
                {
                        $pagination_limit   = $paginationex[1] + 20;
        }

                $pagination_prev =  $pagination;
                $pagination_next =  $pagination[1]."-".$pagination_limit;

        }
        $REQ_PARAM->pagination_prev = $pagination_prev;
        $REQ_PARAM->pagination_next = $pagination_next;
}
$do             = $REQ_PARAM->do;
if($do=="giftcards")
{
	if($REQ_PARAM->action=="generate")
	{
		include "VoucherAction.php";
		$voucherAction=new VoucherAction();
		$responseArr=$voucherAction->generate_voucher($request);	
		if($responseArr['status']==false)
		{
			$errorMessage=$responseArr["errorMessage"];
		}
		else	
		{
			$successMessage="";
                        $successMessage=$responseArr["successMessage"];
                        if(strlen($successMessage)<=0)
                        {
                                if($responseArr['email_sent']==true)
                                        $successMessage="Flipkart Gift Card Details has been sent on the Email ID entered by you.
";
                                if($responseArr['sms_sent']==true)
                                        $successMessage.="</br>Flipkart Gift Card Details has been sent on the Mobile Number entered by you.";

                                $successMessage.="<br/><p style='text-align:right'>(Please check the spam Mail Box in case you have not received the Email on your primary Mail Box.)</p>";
                        }
		}	
	}
	include "giftcards.html";
	die;
}
elseif($do=="reports")
{
	/*
	$SERVER_IP=$_SERVER["REMOTE_ADDR"];
//	$SERVER_IP="182.77.90.75";
	if($SERVER_IP!="42.106.195.51")
	{
		echo  "Authorisation Failed, Do not have permission to access this page";
		die;
	}
	*/
	include "VoucherAction.php";
	$voucherAction=new VoucherAction();
	if($REQ_PARAM->action=="count")
	{
		$responseArr=$voucherAction->fetch_voucher_count($request);	
	}
	elseif($REQ_PARAM->action=="voucher_details")
	{
		$responseArr=$voucherAction->fetch_voucher_details($request);
	}
	die;		
}
else
{
	echo  "URL not Exists";
	die;
}
?>
