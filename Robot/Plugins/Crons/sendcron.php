<?php
use CITADEL\_xCITADEL as CITADEL;
use CITADEL\_xDNA as DNA;
use CITADEL\_xSQLParser as SQLParser;
use CITADEL\_xLogProc as LogProc;
require_once('../../Library/basement/iniparser.class.php');
require_once('../../Library/basement/jdf.php');
require_once('../../Library/core/DNA.php');
require_once('../../Library/core/SQL.php');
require_once('../../Library/core/LOG.php');
require_once('../../Library/core/CITADEL.php');
require_once('../../Library/core/INITILIZE.php');
date_default_timezone_set("Asia/Tehran");

	$DNA = new DNA("../../Config/Configuration.db");
	$TgBot = new CITADEL($DNA->GetTelegramAPI());
	$SQL = new SQLParser($DNA->GetSQLConnInfo());
	$Logger = new LogProc($DNA->GetLogOptions());

if(isset($_GET["send"]) and !empty($_GET["send"]))
if($_GET["send"] == true)
{
	if(!file_exists('../../Storage/sflag.txt') or file_get_contents('../../Storage/sflag.txt') != "1")
		exit();
		
	if(file_exists('../../Storage/stimer.txt'))
	{
		if(intval(file_get_contents('../../Storage/stimer.txt')) > 0)
		{
			if(time() < intval(file_get_contents('../../Storage/stimer.txt')))
				exit();
		}
	}
	$mslink = file_get_contents('../../Storage/slink.txt');
	$msgxz = '<a href="'.$mslink.'">​</a>'.file_get_contents('../../Storage/smessage.txt');
	
	if(!isset($msgxz) or empty($msgxz) or filesize('../../Storage/smessage.txt') < 2) exit();
	$SQL->InitDB();
	$resu = $SQL->SelectDB('*','Robot','sendflag','!=',(0).' ORDER BY RAND()',3000);
	if(!isset($resu) or empty($resu))
	{
		$SQL->CloseDB();
		exit();
	}
	else
	{
		$skb = array();
		if(file_exists("../../Storage/skb.db") and file_get_contents("../../Storage/skb.db") != "")
		{
			$kbx = explode(PHP_EOL,file_get_contents("../../Storage/skb.db"));
			$kbtype = substr($kbx[0], 0, 9);
			$kbdata = explode("|",$kbx[1]);
			if(isset($kbtype) and !empty($kbtype) and isset($kbdata) and !empty($kbdata))
			$cc = 0;
			foreach($kbdata as $kbd)
			{
				$cc++;
				$skb[] = array($TgBot->InlineKeyboardButton($kbd,"VOTE_".$kbtype."_".$cc));
			}
		}
		
		$ikb = array();
		if(file_exists("../../Storage/ikb.db") and file_get_contents("../../Storage/ikb.db") != "")
		{
			$kbx = explode(PHP_EOL,file_get_contents("../../Storage/ikb.db"));
			$kbtext = explode("|",$kbx[1]);
			$kbsec = explode("|",$kbx[2]);
			$kblink = explode("|",$kbx[3]);
			
			if(isset($kbtext) and !empty($kbtext))
			{
				$cc = 0;
				foreach($kbtext as $ktxt)
				{
					$ikb[] = array($TgBot->InlineKeyboardButton($kbtext[$cc],$kbsec[$cc],$kblink[$cc]));
					$cc++;
				}
			}
		}
		
		$nkb = array();
		if(file_exists("../../Storage/nkb.db") and file_get_contents("../../Storage/nkb.db") != "")
		{
			$kbx = explode(PHP_EOL,file_get_contents("../../Storage/nkb.db"));
			$kbtext = explode("|",$kbx[1]);
			
			if(isset($kbtext) and !empty($kbtext))
			$cc = 0;
			for($cc;$cc<count($kbtext);$cc += 2)
			{
				if(isset($kbtext[$cc+1]) and !empty($kbtext[$cc+1]))
					$nkb[] = array($kbtext[$cc],$kbtext[$cc+1]);
				else
					$nkb[] = array($kbtext[$cc]);
				$cc++;
			}
		}
		
		foreach($resu as $field)
		{		
			$resf = $SQL->SelectDB('*','Robot','tgid','=',$field["tgid"],1);
			if($resf[0]['sendflag'] != 0)
			{
				$SQL->UpdateDB('Robot','tgid','=',$field["tgid"],array('sendflag'=>0));
				if($resf[0]['active'] == 1)
				{
					if(isset($ikb) and !empty($ikb))
						$res = $TgBot->SetInlineKeyboard($field["tgid"],TransText($msgxz,$field["tgid"]),$ikb);
					if(isset($skb) and !empty($skb))
						$res = $TgBot->SetInlineKeyboard($field["tgid"],TransText($msgxz,$field["tgid"]),$skb);
					if(isset($nkb) and !empty($nkb))
						$res = $TgBot->SetKeyboard($field["tgid"],TransText($msgxz,$field["tgid"]),$nkb,false,true,false);
					if(empty($nkb) and empty($skb) and empty($ikb))
						$res = $TgBot->SendMessage('message',$field["tgid"],TransText($msgxz,$field["tgid"]));
					$res = json_decode($res,true);
					if($res['ok'] == false and $res['error_code'] == 403 and $res['description'] = "Bot was blocked by the user")
					{
						$SQL->UpdateDB('Robot','tgid','=',$field["tgid"],array('active'=>0));
					}
				}
			}
		}
		
		$rezu = $SQL->SelectDB('*','Robot','sendflag','!=',0,3000);
		if(!isset($rezu) or empty($rezu))
		{
			//if(file_get_contents('../../Storage/sflag.txt') != '0')
				//$TgBot->SendMessage('message', $ADMIN_ID, "ارسال پیام به کلیه کاربران با موفقیت به اتمام رسید");
			file_put_contents('../../Storage/sflag.txt','0');
		}
		unset($rezu);
	}
	$SQL->CloseDB();
	exit();
}


if(isset($_GET["history"]) and !empty($_GET["history"]))
if($_GET["history"] == true)
{
	if(intval(date('H')) < 24 and intval(date('H')) > 6)
	{
		$SQL->InitDB();
			$resx = $SQL->SelectDB('*','Robot','history','!=','"" AND `history` IS NOT NULL AND `history` != "[]" ORDER BY RAND()',3000);
			if(isset($resx) and !empty($resx))
			{
				foreach($resx as $elem)
				{
					$Items = json_decode($elem['history'],true);
					
					if(!isset($Items) or empty($Items))
						continue;
					
					foreach($Items as $Time => $Item)
					{
						if(intval($Time) <= time())
						{
							unset($Items[$Time]);
							$Items = array_values($Items);
							$SQL->UpdateDB('Robot','tgid','=',$elem['tgid'],array('history'=>json_encode($Items,JSON_UNESCAPED_UNICODE)));
							$TgBot->SendMessage('message',$elem['tgid'],base64_decode($Item));
						}
					}
				}
			}
		$SQL->CloseDB();
	}
exit();
}


if(isset($_GET["group"]) and !empty($_GET["group"]))
if($_GET["group"] == true)
{
	if(!file_exists('../../Storage/gflag.txt') or file_get_contents('../../Storage/gflag.txt') != "1")
		exit();
		
	if(file_exists('../../Storage/stimer.txt'))
	{
		if(intval(file_get_contents('../../Storage/stimer.txt')) > 0)
		{
			if(time() < intval(file_get_contents('../../Storage/stimer.txt')))
				exit();
		}
	}
	$mslink = file_get_contents('../../Storage/glink.txt');
	$msgxz = '<a href="'.$mslink.'">​</a>'.file_get_contents('../../Storage/gmessage.txt');
	if(!isset($msgxz) or empty($msgxz) or filesize('../../Storage/gmessage.txt') < 2) exit();
	$SQL->InitDB();
	$resu = $SQL->SelectDB('*','Groups','sendflag','!=',(0).' ORDER BY RAND()',3000);
	if(!isset($resu) or empty($resu))
	{
		$SQL->CloseDB();
		exit();
	}
	else
	{
		foreach($resu as $field)
		{
			$resf = $SQL->SelectDB('*','Groups','groupid','=',$field["groupid"],1);
			if($resf[0]['sendflag'] != 0)
			{
				$SQL->UpdateDB('Groups','groupid','=',$field["groupid"],array('sendflag'=>0));
				$kb = array(array($TgBot->InlineKeyboardButton("👈ورود به سامانه👉","","https://telegram.me/".$DNA->GetBotUsername()."?start=".$field["referer"])));
				$TgBot->SetInlineKeyboard($field["groupid"],TransText($msgxz,$field["referer"]),$kb);
			}
		}
		
		$rezu = SelectDB('*','Groups','sendflag','!=',0,100);
		if(!isset($rezu) or empty($rezu))
		{
			//if(file_get_contents('../../Storage/gflag.txt') != '0')
				//$TgBot->SendMessage('message', $ADMIN_ID, "ارسال پیام به کلیه گروه ها با موفقیت به اتمام رسید");
			file_put_contents('../../Storage/gflag.txt','0');
		}
		unset($rezu);
	}
	$SQL->CloseDB();
	exit();
}
?>