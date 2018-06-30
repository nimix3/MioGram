<?php
/*
	LOG is a core library of CITADEL framework created by NIMIX3.
	LOG library V.1
	ALL RIGHTS RESERVED, PLEASE DO NOT COPY OR SELL THIS PROJECT AND OTHER RELATED MATERIALS.
*/
namespace CITADEL;
class _xLogProc
{
	protected $LastError;
	protected $LastErrors;
	protected $Logdir;
	protected $loglimit;
	
public function __construct($LogOptions)
{
	if(isset($LogOptions['logdir']) and !empty($LogOptions['logdir']))
		$this->Logdir = $LogOptions['logdir'];
	else
		$this->Logdir = '/Storage/Logs';
}

public function LogMessage($errcode,$errtype,$errlocation="none",$errmessage)
{
	if(isset($errcode) and !empty($errcode) and isset($errtype) and !empty($errtype) and isset($errmessage) and !empty($errmessage) and isset($errlocation) and !empty($errlocation))
	{
		if(is_array($errmessage))
			$errmessage = json_encode($errmessage);
		$error = array('ErrorType'=>$errtype,'ErrorCode'=>$errcode,'ErrorLocation'=>$errlocation,'ErrorMessage'=>$errmessage);
		if(file_put_contents($this->Logdir."/".date("Ymd").".txt",json_encode($error),FILE_APPEND) !== false)
		{
			$LastError = json_encode($error);
			$LastErrors[] = json_encode($error);
			return true;
		}
		else
			return false;
	}
return false;
}

public function LogError($errcode,$errtype,$errlocation="none",$errmessage)
{
	if(isset($errcode) and !empty($errcode) and isset($errtype) and !empty($errtype) and isset($errmessage) and !empty($errmessage) and isset($errlocation) and !empty($errlocation))
	{
		if(is_array($errmessage))
			$errmessage = json_encode($errmessage);
		$error = array('ErrorType'=>$errtype,'ErrorCode'=>$errcode,'ErrorLocation'=>$errlocation,'ErrorMessage'=>$errmessage);
		$LastError = json_encode($error);
		$LastErrors[] = json_encode($error);
		return true;
	}
return false;
}

public function ClearLogs()
{
	$this->LastError = null;
	$this->LastErrors = null;
return;	
}


public function GetLastError()
{
	if(isset($this->LastError) and !empty($this->LastError))
	{
		return json_decode($this->LastError,true);
	}
	else
		return null;
}

public function GetError($i=0)
{
	if(isset($this->LastErrors[$i]) and !empty($this->LastErrors[$i]))
	{
		return json_decode($this->LastErrors[$i],true);
	}
	else
		return null;
}

public function GetErrors()
{
	if(isset($this->LastErrors) and !empty($this->LastErrors))
	{
		$output = array();
		if(is_array($this->LastErrors))
		{
			foreach($this->LastErrors as $errors)
			{
				$output[] = json_decode($errors,true);
			}
			return $output;
		}
		else
			return false;
	}
	else
		return null;
}

public function LoadErrors($logname)
{
	if(file_exists($this->Logdir."/".logname.".txt"))
	{
		$data = file_get_contents($this->Logdir."/".logname.".txt");
		if(isset($data) and !empty($data))
		{
			$data = explode(PHP_EOL,$data);
			if(is_array($data))
			{
				foreach($data as $item)
				{
					$this->LastErrors[] = $item;
				}
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	else
		return false;
}

}
?>