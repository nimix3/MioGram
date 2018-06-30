<?php
/*
	SQL is a core library of CITADEL framework created by NIMIX3.
	SQL library V.1.1
	ALL RIGHTS RESERVED, PLEASE DO NOT COPY OR SELL THIS PROJECT AND OTHER RELATED MATERIALS.
*/
namespace CITADEL;
class _xSQLParser
{
	protected $DBserver;
	protected $DBname;
	protected $DBuser;
	protected $DBpass;
	protected $DBport;
	protected $DBcharset;
	protected $DBobj;
	protected $DBQuery;

public function __construct($SQLConfig)
{
	if(is_array($SQLConfig))
	{
		$this->DBserver = $SQLConfig['server'];
		$this->DBname = $SQLConfig['name'];
		$this->DBuser = $SQLConfig['username'];
		$this->DBpass = $SQLConfig['password'];
		$this->DBport = $SQLConfig['port'];
		$this->DBcharset = $SQLConfig['charset'];
		return true;
	}
	return false;	
}


public function __destruct()
{
	return $this->CloseDB();
}


public function InitDB($charset='')
{
	$__DBh = mysqli_connect($this->DBserver, $this->DBuser, $this->DBpass, $this->DBname, (int)$this->DBport);
	if(!$__DBh) {
		return false; //mysqli_connect_error();
	}
	else
	{
		$this->DBobj = $__DBh;
		if(!isset($charset) or empty($charset))
			$charset = $this->DBcharset;
		@ mysqli_set_charset($this->DBobj, $charset);
		@ mysqli_query($this->DBobj,"SET NAMES ".$charset);
		return true;
	}
return;
}


public function InitAliveDB($charset='')
{
	$__DBh = mysqli_connect("p:".$this->DBserver, $this->DBuser, $this->DBpass, $this->DBname, (int)$this->DBport);
	if (!$__DBh) {
		return false;
	}
	else
	{
		$this->DBobj = $__DBh;
		if(!isset($charset) or empty($charset))
			$charset = $this->DBcharset;
		@ mysqli_set_charset($this->DBobj, $charset);
		@ mysqli_query($this->DBobj,"SET NAMES ".$charset);
		return true;
	}
return;
}


public function StatusDB()
{
        if(!$this->DBobj)
			return false;
		if(mysqli_ping($this->DBobj))
			return true;
		else
			return false;
}


public function KillDB()
{
        if(!$this->DBobj)
			return false;
		if(mysqli_kill($this->DBobj,mysqli_thread_id($this->DBobj)))
			return true;
		else
			return false;
}


public function RollBackDB()
{
        if(!$this->DBobj)
			return false;
		if(mysqli_rollback($this->DBobj))
			return true;
		else
			return false;
}


public function CloseDB()
{
	if($this->StatusDB())
	{
		@ mysqli_close($this->DBobj);
		@ $this->DBobj = null;
		return true;
	}
	else
		return false;
return false;
}


public function GetQueryDB()
{
	if(isset($this->DBQuery) and !empty($this->DBQuery))
		return $this->DBQuery;
	else
		return null;
}


public function DebugDB()
{
	if($this->StatusDB())
	{
		return mysqli_error($this->DBobj);
	}
	else
	return null;
return null;
}


public function SecureDBQuery($str,$dbc)
{
	if($dbc !== true)
	{
		$str = str_replace('(','',$str);
		$str = str_replace(')','',$str);
		$str = str_replace('[','',$str);
		$str = str_replace(']','',$str);
		$str = str_replace('{','',$str);
		$str = str_replace('}','',$str);
		$str = str_replace('*','',$str);
		$str = str_replace('?','',$str);
		$str = str_replace('!','',$str);
		$str = str_replace(';','',$str);
		$str = str_replace('&','',$str);
		$str = str_replace('%','',$str);
		$str = str_replace('-','',$str);
		$str = str_replace('_','',$str);
		$str = str_replace("'",'',$str);
		$str = str_replace(':"','',$str);
		$str = str_replace('~','',$str);
		$str = str_replace('`','',$str);
		$str = str_replace('@','',$str);
		$str = str_replace('^','',$str);
		$str = str_replace('=','',$str);
		$str = str_replace('|','',$str);
		$str = str_replace('<','',$str);
		$str = str_replace('>','',$str);
		$str = str_replace('/','',$str);
		$str = str_replace('\\','',$str);
		$str = str_replace('+','',$str);
		$str = str_replace('.','',$str);	
	}
	if($this->StatusDB())
	{
		$str = mysqli_real_escape_string($this->DBobj,$str);
	}
	return $str;
return;
}


public function PSelectDB($select,$from,$statement,$limit = 1)
{
	
}


public function SelectDB($select,$from,$where,$sign,$statement,$limit = 1,$xquery='')
{
	@ $statement = str_replace("'","''",$statement);
	if(isset($where) and !empty($where))
	$sql = "SELECT ".$select." FROM `".$from."` WHERE ".$where.$sign.$statement." ".$xquery." LIMIT ".$limit;
	else
	$sql = "SELECT ".$select." FROM `".$from."` ".$xquery." LIMIT ".$limit;
	$this->DBQuery = $sql;
	if(!$this->StatusDB())
		$this->InitDB();
	if($this->StatusDB())
	{
		@ $result = mysqli_query($this->DBobj,$sql);
		if(!isset($result) or empty($result) or is_bool($result))
			return null;
		if(mysqli_num_rows($result) <= 0)
			return null;
		while ($row = mysqli_fetch_assoc($result)) {
			$res[] = $row;
		}
		mysqli_free_result($result);
		return $res;
	}
	else
	{
		return null;
	}
return null;
}


public function UpdateDB($table,$where,$sign,$statement,$data,$limit=1)
{
	$d = "";
	foreach($data as $key => $val)
	{
			@ $val = str_replace("'","''",$val);
			$d .= '`'.$key.'`="'.$val.'",';
	}
	$d = trim($d, ",");
	$sql = "UPDATE `$table` SET ".$d." WHERE ".$where.$sign.$statement." LIMIT ".$limit;
	$this->DBQuery = $sql;
	if(!$this->DBobj)
	$this->InitDB();
	if($this->DBobj)
	{
		@ $result = mysqli_query($this->DBobj,$sql);
		if(!$result)
		return false;
		else
		return true;
	}
	else
		return false;
return;
}


public function DeleteDB($table,$where,$sign,$statement,$limit=1)
{
	@ $statement = str_replace("'","''",$statement);
	$sql = "DELETE FROM `$table` WHERE ".$where.$sign.$statement." LIMIT ".$limit;
	$this->DBQuery = $sql;
	if(!$this->DBobj)
	$this->InitDB();
	if($this->DBobj)
	{
		@ $result = mysqli_query($this->DBobj,$sql);
		if(!$result)
		return false;
		else
		return true;
	}
	else
	return false;
return;
}


function InsertDB($table,$data)
{
	$d = "";
	$e = "";
	foreach($data as $key => $val)
	{
		@ $val = str_replace("'","''",$val);
		$d .= "`$key`,";
		$e .= "'$val',";
	}
	$d = trim($d, ",");
	$e = trim($e, ",");
		
	$sql = "INSERT INTO `$table` (".$d.") VALUES (".$e.")";
	$this->DBQuery = $sql;
	if(!$this->DBobj)
	$this->InitDB();
	if($this->DBobj)
	{
		@ $result = mysqli_query($this->DBobj,$sql);
		if(!$result)
			return false;
		else
			return true;
	}
	else
	return false;
return;
}

}
?>