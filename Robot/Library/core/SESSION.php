<?php
/*
	SESSION is a core library of CITADEL framework created by NIMIX3.
	SESSION library V.1
	ALL RIGHTS RESERVED, PLEASE DO NOT COPY OR SELL THIS PROJECT AND OTHER RELATED MATERIALS.
*/
class Session
{
	protected $LastError;
	
	public function __construct()
	{
		try{
			return session_start();
		}
		catch(Exception $ex)
		{
			$this->LastError[] = "Exception occurred";
			return null;
		}
	}
		
	public function InitSession()
	{
		try{
			return session_start();
		}
		catch(Exception $ex)
		{
			$this->LastError[] = "Exception occurred";
			return null;
		}
	}
	
	public function DestroySession($full = true)
	{
		try{
			if((bool)$full)
			{
				session_unset();
				session_destroy(); 
			}
			else
			{
				session_unset();
			}
		}
		catch(Exception $ex)
		{
			$this->LastError[] = "Exception occurred";
			return null;
		}
	}
	
	public function SessionID()
	{
		try{
			if(session_start())
				return session_id();
			return null;
		}
		catch(Exception $ex)
		{
			$this->LastError[] = "Exception occurred";
			return null;
		}
	}
	
	public function RevokeSession()
	{
		try{
			return session_regenerate_id();
		}
		catch(Exception $ex)
		{
			$this->LastError[] = "Exception occurred";
			return null;
		}
	}
	
	public function SessionSetData($data)
	{
		try{
			if(is_array($data))
			{
				$_SESSION = $data;
				return true;
			}
			return false;
		}
		catch(Exception $ex)
		{
			$this->LastError[] = "Exception occurred";
			return null;
		}
	}
	
	public function SessionGetData()
	{
		try{
			if(isset($_SESSION) and !empty($_SESSION))
			{
				return $_SESSION;
			}
			return null;
		}
		catch(Exception $ex)
		{
			$this->LastError[] = "Exception occurred";
			return null;
		}
	}
	
	public function getLastError()
	{
		return $this->LastError;
	}
}
?>