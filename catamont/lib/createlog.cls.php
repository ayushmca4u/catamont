<?php
Class CreateLog
{
	var $fp_info_log;
	var $fp_info_log_csv;
	var $logging_level;
	var $tabs;
	var $log_module;
	var $log_key;
	var $logs = array("INFO" => "1","ERROR" => "2","QUERY" => "3","FUNC" => "4","DEBUG" => "10");

	//Oracle Constructor
	function CreateLog($log_level,$log_module,$log_key='',$dir_name='')
	{
		$this->log_key = $log_key;
		$this->logging_level = $this->logs[$log_level];
		if(isset($dir_name) && strlen($dir_name)>0)
		{
			$this->log_module = date("Ym").$log_module;
			if(!file_exists(LOGINCLUDEPATH.$dir_name))
			{
				mkdir(LOGINCLUDEPATH.$dir_name, 0777); 	
			}
			$this->fp_info_log = fopen(LOGINCLUDEPATH.$dir_name."/".$this->log_module.".log","a+");
		}
		else
		{
			$this->log_module = date("Ymd").$log_module;
			$this->fp_info_log = fopen(LOGINCLUDEPATH.$this->log_module.".log","a+");
		}
	}

	function set_log_key($log_key)
	{		
		$this->log_key = $log_key;
	}

	function logging($level,$data,$function="",$line="") 
	{
		//GLOBAL $request_obj;
		$level_id = $this->logs[$level];

		if($level_id <= $this->logging_level) 
		{
			if($this->log_key)
			{
				$str=$this->log_key."\t";
			}	
			$str .=date("Ymd\tH:i:s");
			$str.="\t".$level;
			if($function) 
			{
				$str .= "\t".$function;
			}
			if($line) 
			{
				$str .= "\t".$line;
			}
			$str .= "\t".$data."\n";
			fwrite($this->fp_info_log,$str);
		}
	}

}
?>
