<?php
class VoucherAction
{
	function __construct()
        {

        }
	public function generate_voucher($request)
	{	
		$vcode=$request->getParameter("vcode");
		$byemail=$request->getParameter("byemail");
		$bysms=$request->getParameter("bysms");
		$mobileno=$request->getParameter("mobileno");
		$email =$request->getParameter("email");				
		include "/home/httpd/htdocs/catamont/include/config.php";
		include "/home/httpd/htdocs/catamont/lib/mysqli_conn.cls.php";
		include "/home/httpd/htdocs/catamont/lib/createlog.cls.php";

		$LOG_FILE_HANDLER=new Createlog('DEBUG',"generate_voucher");
		$LOG_FILE_HANDLER->logging('DEBUG',"START generation php ");
		$mysqlObj=new mysqlDatabase(DB_USERNAME,DB_PASSWORD,DATABASE,SERVERIP,$LOG_FILE_HANDLER);
	}
}
?>
