<?php
use CITADEL\_xCITADEL as CITADEL;
use CITADEL\_xDNA as DNA;
use CITADEL\_xSQLParser as SQLParser;
use CITADEL\_xLogProc as LogProc;
require_once('../Robot/Library/basement/iniparser.class.php');
require_once('../Robot/Library/basement/jdf.php');
require_once('../Robot/Library/core/DNA.php');
require_once('../Robot/Library/core/SQL.php');
require_once('../Robot/Library/core/LOG.php');
require_once('../Robot/Library/core/CITADEL.php');
require_once('../Robot/Library/core/INITILIZE.php');
date_default_timezone_set("Asia/Tehran");

	$DNA = new DNA("../Robot/Config/Configuration.db");
	$TgBot = new CITADEL($DNA->GetTelegramAPI());

if(isset($_REQUEST['fid']) and !empty($_REQUEST['fid']))
{
	$url = $TgBot->GetFileUrl($_REQUEST['fid']);
	if(http_response($url)) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.basename($url));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . http_response($url,true));
        ob_clean();
        flush();
        readfile($url);
        exit();
        }
	else
		die("File Not Found!");
}
else
{
	die("Invalid Request!");
}


function http_response($url,$inf=false){
    $resURL = curl_init(); 
    curl_setopt($resURL, CURLOPT_URL, $url); 
    curl_setopt($resURL, CURLOPT_BINARYTRANSFER, 1); 
    //curl_setopt($resURL, CURLOPT_HEADERFUNCTION, array(&$this,'curlHeaderCallback')); 
    curl_setopt($resURL, CURLOPT_FAILONERROR, 1); 
    curl_exec ($resURL); 
    $intReturnCode = curl_getinfo($resURL, CURLINFO_HTTP_CODE);
    $size = curl_getinfo($resURL, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
    curl_close ($resURL);
    if($inf)
    return $size;
    else
    if ($intReturnCode != 200 && $intReturnCode != 302 && $intReturnCode != 304) { return 0; } else return 1;
}
?>