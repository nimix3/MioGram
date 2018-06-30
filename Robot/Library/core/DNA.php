<?php
/*
	DNA is a core library of CITADEL framework created by NIMIX3.
	DNA library V.1
	ALL RIGHTS RESERVED, PLEASE DO NOT COPY OR SELL THIS PROJECT AND OTHER RELATED MATERIALS.
*/
namespace CITADEL;
class _xDNA
{

/// Property
protected $Config;

/// Constructor
public function __construct($CONFile)
{
	if(isset($CONFile) and !empty($CONFile))
	{
		if(file_exists($CONFile))
		{
			try
			{
				$this->Config = parse_ini_file($CONFile);
				return true;
			}
			catch(Exception $e)
			{
				file_put_contents("Assets/Log.txt",json_encode(array('error'=>'196','desc'=>$e->getMessage(),'loc'=>'AutoLoaderConstruct::')).PHP_EOL,FILE_APPEND,FILE_APPEND);
				return false;
			}
		}
		else
		{
			if(file_exists($this->GetConfFile()))
			{
				try
				{
					$this->Config = parse_ini_file($CONFile);
					return true;
				}
				catch(Exception $e)
				{
					file_put_contents("Assets/Log.txt",json_encode(array('error'=>'197','desc'=>$e->getMessage(),'loc'=>'AutoLoaderConstruct::')).PHP_EOL,FILE_APPEND);
					return false;
				}
			}
			return false;
		}
	}
	else
	{
		if(file_exists($this->GetConfFile()))
		{
			try
			{
				$this->Config = parse_ini_file($CONFile);
				return true;
			}
			catch(Exception $e)
			{
				file_put_contents("Assets/Log.txt",json_encode(array('error'=>'198','desc'=>$e->getMessage(),'loc'=>'AutoLoaderConstruct::')).PHP_EOL,FILE_APPEND);
				return false;
			}
		}
		return false;
	}
return false;
}

/// AutoGetLibraries
public function AutoGetLibraries($dir)
{
if(!isset($dir) or empty($dir))
	$dir = $this->GetBaseLibraryDir();
$files = array();
@ $items = array_values(array_diff(scandir($dir),array('.', '..')));
if(isset($items) and !empty($items))
{
	foreach($items as $item)
	{
		if(is_file($dir."/".$item) && substr($item,-4) == '.php')
			$files[] = str_replace(".php","",$item);
	}
}
else
{
	return array();
}
return $files;
}

/// AutoLoadLibraries
public function AutoLoadLibraries($dir)
{
if(!isset($dir) or empty($dir))
	$dir = $this->GetBaseLibraryDir();
@ $items = array_values(array_diff(scandir($dir),array('.', '..')));
if(isset($items) and !empty($items))
{
	foreach($items as $item)
	{
		if(is_file($dir."/".$item) && substr($item,-4) == '.php')
		{
			try
			{
				require_once($dir."/".$item);
			}
			catch(Exception $e)
			{
				file_put_contents("Assets/Log.txt",json_encode(array('error'=>'176','desc'=>$e->getMessage(),'loc'=>'AutoLoadLibraries::'.$item)).PHP_EOL,FILE_APPEND);
			}
		}
	}
}
else
{
	return false;
}
return true;
}

/// AutoLoadHookPlugins
public function AutoLoadHookPlugins($BotObj,$DBObj,$DBCache,$DNA,$Logger,$Lang='default',$dir='')
{
if(!isset($dir) or empty($dir))
	$dir = $this->GetHookPluginDir();
$items = parse_ini_file($this->GetHookPluginDir()."/plugins.db");
asort($items);
if(isset($items) and !empty($items))
{
	foreach($items as $item => $per)
	{
		if(is_file($dir."/".$item) && substr($item,-4) == '.php' && $per >= 0)
		{
			try
			{
				if(file_exists($dir."/".$item))
				{
					unset($Action,$PreRequirement);
					require_once($dir."/".$item);
					try
					{
						$DataLoad = array();
						if($dir."/".str_replace(".php","",$item).".dat")
							$DataLoad = parse_ini_file($dir."/".str_replace(".php","",$item).".dat");
						$pres = $PreRequirement($BotObj,$DBObj,$DNA,$Logger,$DataLoad);
						$ares = $Action($BotObj,$DBObj,$DBCache,$DNA,$Logger,$Lang,$DataLoad);
						if((bool)$ares == true)
							exit();
					}
					catch(Exception $e)
					{
						file_put_contents("Assets/Log.txt",json_encode(array('error'=>'191','desc'=>$e->getMessage(),'loc'=>'AutoLoadPluginItem::'.$item)).PHP_EOL,FILE_APPEND);
						return false;
					}
				}
			}
			catch(Exception $e)
			{
				file_put_contents("Assets/Log.txt",json_encode(array('error'=>'192','desc'=>$e->getMessage(),'loc'=>'AutoLoadPlugins::'.$item)).PHP_EOL,FILE_APPEND);
				return false;
			}	
		}
	}
}
else
{
	return false;
}
return true;
}

/// AutoLoadCronPlugins
public function AutoLoadCronPlugins($BotObj,$DBObj,$DBCache,$DNA,$Logger,$Lang='default',$dir='')
{
if(!isset($dir) or empty($dir))
	$dir = $this->GetCronPluginDir();
$items = parse_ini_file($this->GetCronPluginDir()."/crons.db");

if(file_exists($this->GetCronPluginDir()."/cache.dat"))
	$timecache = json_decode(file_get_contents($this->GetCronPluginDir()."/cache.dat"),true);
else
	$timecache = array();

if(isset($items) and !empty($items))
{
	foreach($items as $item => $per)
	{
		if(intval($timecache[$item]) + intval($per) > time() or intval($per) < 0)
			continue;
		if(is_file($dir."/".$item) && substr($item,-4) == '.php' && $per >= 0)
		{
			try
			{
				if(file_exists($dir."/".$item))
				{
					unset($Action,$PreRequirement);
					require_once($dir."/".$item);
					try
					{
						$DataLoad = array();
						if($dir."/".str_replace(".php","",$item).".dat")
							$DataLoad = parse_ini_file($dir."/".str_replace(".php","",$item).".dat");
						$timecache[$item] = time();
						$PreRequirement($BotObj,$DBObj,$DNA,$Logger,$DataLoad);
						$res = $Action($BotObj,$DBObj,$DBCache,$DNA,$Logger,$Lang,$DataLoad);
						file_put_contents($this->GetCronPluginDir()."/cache.dat",json_encode($timecache));
						if((bool)$res == true)
							exit();
					}
					catch(Exception $e)
					{
						file_put_contents("Assets/Log.txt",json_encode(array('error'=>'191','desc'=>$e->getMessage(),'loc'=>'AutoLoadPluginItem::'.$item)).PHP_EOL,FILE_APPEND);
						return false;
					}
				}
			}
			catch(Exception $e)
			{
				file_put_contents("Assets/Log.txt",json_encode(array('error'=>'192','desc'=>$e->getMessage(),'loc'=>'AutoLoadPlugins::'.$item)).PHP_EOL,FILE_APPEND);
				return false;
			}	
		}
	}
}
else
{
	return false;
}
return true;
}

/// Fast Get Directories & Settings
public function GetAssetsDir()
{
	if(isset($this->Config['AssetsDir']) and !empty($this->Config['AssetsDir']))
		return $this->Config['AssetsDir'];
	return 'Assets';
}

public function GetStorageDir()
{
	if(isset($this->Config['StorageDir']) and !empty($this->Config['StorageDir']))
		return $this->Config['StorageDir'];
	return 'Storage';
}

public function Get3rdpLibraryDir()
{
	if(isset($this->Config['3rdpLibraryDir']) and !empty($this->Config['3rdpLibraryDir']))
		return $this->Config['3rdpLibraryDir'];
	return 'Library/3rdparty';
}

public function GetHookPluginDir()
{
	if(isset($this->Config['HookPluginDir']) and !empty($this->Config['HookPluginDir']))
		return $this->Config['HookPluginDir'];
	return 'Plugins/Hooks';
}

public function GetCronPluginDir()
{
	if(isset($this->Config['CronPluginDir']) and !empty($this->Config['CronPluginDir']))
		return $this->Config['CronPluginDir'];
	return 'Plugins/Crons';
}

public function GetBaseLibraryDir()
{
	if(isset($this->Config['BaseLibraryDir']) and !empty($this->Config['BaseLibraryDir']))
		return $this->Config['BaseLibraryDir'];
	return 'Library/basement';
}

public function GetWebsiteUrl()
{
	if(isset($this->Config['Website']) and !empty($this->Config['Website']))
		return $this->Config['Website'];
	return null;
}

public function GetBaseDir()
{
	if(isset($this->Config['BaseDir']) and !empty($this->Config['BaseDir']))
		return $this->Config['BaseDir'];
	return null;
}

public function GetBotUsername()
{
	if(isset($this->Config['BotUsername']) and !empty($this->Config['BotUsername']))
		return $this->Config['BotUsername'];
	return null;
}

public function GetTelegramAPI()
{
	if(isset($this->Config['BotAPI']) and !empty($this->Config['BotAPI']))
		return $this->Config['BotAPI'];
	return null;
}

public function GetSQLConnInfo()
{
	if(isset($this->Config['DBserver']) and !empty($this->Config['DBserver']))
		return @ array('server'=>$this->Config['DBserver'],'username'=>$this->Config['DBuser'],'password'=>$this->Config['DBpass'],'name'=>$this->Config['DBname'],'port'=>$this->Config['DBport'],'charset'=>$this->Config['DBcharset']);
	return null;
}

public function GetLogOptions()
{
	if(isset($this->Config['Logdir']) and !empty($this->Config['Logdir']))
		return @ array('logdir'=>$this->Config['Logdir']);
	return null;
}

public function GetAdminOptions()
{
	if(isset($this->Config['Username']) and !empty($this->Config['Username']))
		return @ array('Username'=>$this->Config['Username'],'Password'=>$this->Config['Password']);
	return null;
}
}
?>