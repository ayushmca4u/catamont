<?php
class mysqlDatabase
{
	var $password;
	var $database;
	var $dblink;
	var $toaddr;
	var $direct_email = 0;
	var $Row;

	// Loging enable.
	var $LOG_FILE_HANDLER;

	//Miscellaneous variables.
	var $function_name;
	var $line_no;
	var $file_name;

	function mysqlDatabase($USERNAME,$PASSWORD,$DATABASE,$SERVER,$LOG_FILE_HANDLER='') 
	{
		if(!$LOG_FILE_HANDLER) 
		{
			$this->LOG_FILE_HANDLER = new Createlog('ERROR',"utility_mysql");
		
		} 
		else 
		{
			$this->LOG_FILE_HANDLER=$LOG_FILE_HANDLER;
		}

		$this->username = $USERNAME;
		$this->database = $DATABASE;
		$this->serverip = $SERVER;
		$this->password = $PASSWORD;

		$this->set_email_report("sulakshn@rediff.co.in");

		$this->dblink=mysql_connect($this->serverip, $this->username, $this->password)
		or $this->error(mysql_error()." Mysql connect error at ".date()." on ".$this->server);

		mysql_select_db($this->database,$this->dblink)
		or $this->error("Mysql select db error at ".@date()." on ".$this->server);

		register_shutdown_function(array( &$this, "cleanup" ));
	}
	
	function change_db($db)
	{
#		$this->LOG_FILE_HANDLER->logging('DEBUG',"DATABASE:".$db);
		mysql_select_db($db,$this->dblink)
		or $this->error("Mysql select db error at ".@date()." on ".$this->server);
		$sql = "set sql_mode=STRICT_TRANS_TABLES;SHOW WARNINGS;";
		$this->getresource($sql);
		
	}
	
	function getresource($query) 
	{
		$this->LOG_FILE_HANDLER->logging('QUERY',"$query");
		$QueryStart = microtime();
		$result = mysql_query($query,$this->dblink);

		if(mysql_errno())
		{
			$this->error("mysqldb::getresource::Query failed");
			$this->error(mysql_error());
		}
		$QueryEnd = microtime();
                $QueryExecutionTime = $QueryEnd - $QueryStarted;
                        
		return $result;
	}

	function get_mysql_error() 
	{
		return mysql_error();
	}
	
	function getarray($query,$debug=false) 
	{
		$QueryStart = microtime();
		if($debug)
		{
			$query1="select database()";
			$result1 = mysql_query($query1,$this->dblink);
			while($row=mysql_fetch_array($result1,MYSQL_BOTH)) 
			{
				$this->error($row);
			}
		}

		$this->LOG_FILE_HANDLER->logging('QUERY',"$query");
		$result = mysql_query($query,$this->dblink);

		if(mysql_errno())
		{
			$this->error("mysqldb::getresource::Query failed ($query)");
			$this->error(mysql_error());
		}
		
		if($debug)
		{
			$this->error(print_r($result,true));
			$this->error($this->getlastquerystatus());
		}

		if(is_resource($result)) 
		{
			if($debug)
			{
				$this->error("IF");
			}

			while($row=mysql_fetch_assoc($result)) 
			{
				if($debug)
				{
					$this->error(print_r($row,true));
				}
				
				$multiarray[]=$row;
			}

			mysql_free_result($result);
			$QueryEnd = microtime();
                	$QueryExecutionTime = $QueryEnd - $QueryStarted;
			return $multiarray;
		} 
		else 
		{
			if($debug)
			{
				$this->error("ELSE");
			}
			$QueryEnd = microtime();
        	        $QueryExecutionTime = $QueryEnd - $QueryStarted;
			return $result;
		}
	}

	function getarray_key($query) 
	{
		$this->LOG_FILE_HANDLER->logging('QUERY',"$query");
		$result = mysql_query($query,$this->dblink);

		if(mysql_errno())
		{
			$this->error("mysqldb::getresource::Query failed ($query)");
			$this->error(mysql_error());
		}

		if(is_resource($result)) 
		{
			while($row=mysql_fetch_array($result)) 
			{
				$multiarray[$row[0]]=$row;
			}

			mysql_free_result($result);
			return $multiarray;
		} 
		else 
		{
			return $result;
		}
	}

	function getcount($query) 
	{
		$this->LOG_FILE_HANDLER->logging('QUERY',"$query");
		$result = mysql_query($query,$this->dblink);

		if(mysql_errno())
		{
			$this->error("mysqldb::getresource::Query failed");
			$this->error(mysql_error());
		}

		if($row=mysql_fetch_array($result)) 
		{
			$this->LOG_FILE_HANDLER->logging('QUERY',"count(*)=".$row[0]);
			return $row[0];
		}

		$this->LOG_FILE_HANDLER->logging('QUERY',"count(*)=-1");
		return -1;
	}

	function getcsv_column($query,$columnNo=0) 
	{
		$this->LOG_FILE_HANDLER->logging('QUERY',"$query");
		$result = mysql_query($query,$this->dblink);

		if(mysql_errno())
		{
			$this->error("mysqldb::getresource::Query failed");
			$this->error(mysql_error());
		}

		$csvstr="";
		while($row=mysql_fetch_row($result)) 
		{
			if (is_null($row[$columnNo]))
			$csvstr.="";
			else
			$csvstr.=",".$row[$columnNo];
		}
		$csvstr=substr($csvstr,1);
		$this->LOG_FILE_HANDLER->logging('QUERY',"$csvstr");
		return $csvstr;
	}

	function getcsv($query) 
	{
		$this->LOG_FILE_HANDLER->logging('QUERY',"$query");
		$result = mysql_query($query,$this->dblink);

		if(mysql_errno())
		{
			$this->error("mysqldb::getresource::Query failed");
			$this->error(mysql_error());
		}

		$csvstr="";
		while($row=mysql_fetch_row($result)) 
		{
			$csvstrrow = "";
			foreach ($row as $column) 
			{
				$csvstrrow .= ",\"".$column."\"";
			}
			$csvstrrow=substr($csvstrrow,1);
			$csvstr.=$csvstrrow."\r\n";
		}
		$this->LOG_FILE_HANDLER->logging('QUERY',"$csvstr");
		return $csvstr;
	}

	function export_mail($query) 
	{
		$result=$this->getcsv($query);
		mail($this->toaddr,"Rediff - Utils - Mysql Connection - Export","$result");
	}

	function get_mysql_affected_rows()
	{
		return mysql_affected_rows($this->dblink);
	}

	function error($message) 
	{
		$this->LOG_FILE_HANDLER->logging('ERROR',"$message");
		$this->error_stack[]="$message";
	}

	function set_email_report($toaddr='')
	{
		if($toaddr)
		{
			$this->toaddr = $toaddr;
		}
		else
		{
			$this->toaddr = "";
		}
	}

	function get_mysql_insert_id() 
	{
		return mysql_insert_id($this->dblink);
	}

	function getlastquerystatus() 
	{
		return mysql_affected_rows();
	}

	function getmysqlerror()
	{
		if(mysql_errno($this->dblink))
		{
			return array(mysql_errno($this->dblink),mysql_error($this->dblink));
		}
	}

	function freeresult($result) 
	{
		mysql_free_result($result);
	}

	function getlastid() 
	{
		$result = mysql_insert_id($this->dblink);
		return $result;
	}

	function cleanup() 
	{
		if ($this->error_stack) 
		{
			$error_stack_str=implode("\n",$this->error_stack);
			mail($this->toaddr,"Rediff - Utils - Mysql connection - Error","$error_stack_str");
		}
	}
	function begin_transaction(){
		$sql = 'SET autocommit=0';
		if (mysql_query($sql,$this->dblink)) {
			$sql = 'START TRANSACTION';
			if(!mysql_query($sql,$this->dblink)){
				die('Start Transaction Failed: ' . mysql_error($this->dblink)."<br>SQL:".$sql);
			}
		}
		else{
			die('Set commit mode Failed: ' . mysql_error($this->dblink)."<br>SQL:".$sql);
		}
		return TRUE;
	}
	function commit_transaction(){
		$sql = 'COMMIT';
		if(!mysql_query($sql,$this->dblink)){
			return FALSE;
		}
		else{
			return TRUE;
		}
	}
	function rollback_transaction(){
		
		$sql = 'ROLLBACK';
		if (!mysql_query($sql,$this->dblink)) {
			error_log('Rollback Failed: ' . mysql_error($this->dblink)."<br>SQL:".$sql);
#return FALSE;
		}
		return TRUE;
	}

}
?>
