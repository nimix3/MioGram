<?php
/////////////////////////////////////////////////////////////////////////////////////
/// Foundation
use CITADEL\_xCITADEL as CITADEL;
use CITADEL\_xDNA as DNA;
use CITADEL\_xSQLParser as SQLParser;
use CITADEL\_xLogProc as LogProc;
require_once("Library/core/CITADEL.php");
require_once("Library/core/DNA.php");
require_once("Library/core/SQL.php");
require_once("Library/core/LOG.php");
/////////////////////////////////////////////////////////////////////////////////////
/// Initilizer
@ ini_set('memory_limit', '-1');
try
{
	require_once("Library/core/INITILIZE.php");
	$DNA = new DNA("Config/Configuration.db");
	$TgBot = new CITADEL($DNA->GetTelegramAPI());
	$SQL = new SQLParser($DNA->GetSQLConnInfo());
	$Logger = new LogProc($DNA->GetLogOptions());
}
catch(Exception $e)
{
	file_put_contents("Assets/Log.txt",json_encode(array('error'=>'171','desc'=>$e->getMessage(),'loc'=>'Initilizer')).PHP_EOL,FILE_APPEND);
}
$SQL->InitDB();
$DBCache = $SQL->SelectDB('*','Robot','tgid','=',$TgBot->GetUserID());
if(isset($DBCache[0]) and !empty($DBCache[0]))
	$DBCache = $DBCache[0];
/////////////////////////////////////////////////////////////////////////////////////
/// AutoLoader (Libraries)
if(!$DNA->AutoLoadLibraries($DNA->GetBaseLibraryDir()))
	file_put_contents("Assets/Log.txt",json_encode(array('error'=>'212','desc'=>'Error when loading basement libraries','loc'=>'ActionAutoLoader::Libraries')).PHP_EOL,FILE_APPEND);
/////////////////////////////////////////////////////////////////////////////////////
/// AutoLoader (3rdparties)
if(!$DNA->AutoLoadLibraries($DNA->Get3rdpLibraryDir()))
	file_put_contents("Assets/Log.txt",json_encode(array('error'=>'213','desc'=>'Error when loading 3rdparty libraries','loc'=>'ActionAutoLoader::3rdparties')).PHP_EOL,FILE_APPEND);
/////////////////////////////////////////////////////////////////////////////////////
/// AutoLoader (Hook Plugins)
if(!$DNA->AutoLoadHookPlugins($TgBot,$SQL,$DBCache,$DNA,$Logger))
	file_put_contents("Assets/Log.txt",json_encode(array('error'=>'216','desc'=>'Error when loading hook plugins','loc'=>'ActionAutoLoader::LoadHooks')).PHP_EOL,FILE_APPEND);
/////////////////////////////////////////////////////////////////////////////////////
/// AutoLoader (Cronjob Plugins)
if(!$DNA->AutoLoadCronPlugins($TgBot,$SQL,$DBCache,$DNA,$Logger))
	file_put_contents("Assets/Log.txt",json_encode(array('error'=>'228','desc'=>'Error when loading cron plugins','loc'=>'ActionAutoLoader::LoadCrons')).PHP_EOL,FILE_APPEND);
/////////////////////////////////////////////////////////////////////////////////////
$SQL->CloseDB();
exit();
?>