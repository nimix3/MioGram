<?php
/*
	INITILIZE is a core library of CITADEL framework created by YOU to customize your usage.
	INITILIZE library V.x
	ALL RIGHTS RESERVED, PLEASE DO NOT COPY OR SELL THIS PROJECT AND OTHER RELATED MATERIALS.
*/
function GenerateID($max = 8)
{
	if(intval($max) <= 1)
		return mt_rand(0,9);
	else if(intval($max) <= 2)
		return mt_rand(0,99);
	else if(intval($max) > 11)
		return substr(substr(time(),-8).mt_rand(10000,9999999).rand(0,9999999),0,intval($max));
	else
		return substr(mt_rand(1000,999999).rand(1000,9999999).substr(time(),-4),0,intval($max));
}


function uid2tgid($uid)
{
	global $DBCache;
	global $SQL;
	if(isset($DBCache) and !empty($DBCache))
	{
		return $DBCache['tgid'];
	}
	else
	{
		if($SQL->StatusDB())
		{
			$DBCache = $SQL->SelectDB('*','Robot','uid','= "',$uid.'"');
			if(isset($DBCache) and !empty($DBCache))
				return $DBCache['tgid'];
			else
				return null;
		}
		else
		{
			$SQL->InitDB();
				$DBCache = $SQL->SelectDB('*','Robot','uid','= "',$uid.'"');
			$SQL->CloseDB();	
				if(isset($DBCache) and !empty($DBCache))
					return $DBCache['tgid'];
				else
					return null;
		}
	}
}

function tgid2uid($tgid)
{
	global $DBCache;
	global $SQL;
	if(isset($DBCache) and !empty($DBCache))
	{
		return $DBCache['uid'];
	}
	else
	{
		if($SQL->StatusDB())
		{
			$DBCache = $SQL->SelectDB('*','Robot','tgid','= "',$tgid.'"');
			if(isset($DBCache) and !empty($DBCache))
				return $DBCache['uid'];
			else
				return null;
		}
		else
		{
			$SQL->InitDB();
				$DBCache = $SQL->SelectDB('*','Robot','tgid','= "',$tgid.'"');
			$SQL->CloseDB();	
				if(isset($DBCache) and !empty($DBCache))
					return $DBCache['uid'];
				else
					return null;
		}
	}
}

function TransText($str,$data='')
{
	global $TgBot;
	global $DNA;
	$str = str_replace("%RAND%",mt_rand(0,99999999),$str);
	$str = str_replace("%LINK%",'https://t.me/'.$DNA->GetBotUsername().'?start='.$data,$str);
	$str = str_replace("%FirstName%",$TgBot->GetFirstName(),$str);
	$str = str_replace("%LastName%",$TgBot->GetLastName(),$str);
	$str = str_replace("%Username%",$TgBot->GetUsername(),$str);
	$str = str_replace("%TGID%",$TgBot->GetUserID(),$str);
	$str = str_replace("%MsgDate%",$TgBot->GetMessageDate(),$str);
	$str = str_replace("%MsgID%",$TgBot->GetMessageID(),$str);
	$str = str_replace("%MsgCaption%",$TgBot->GetMessageCaption(),$str);
	$str = str_replace("%ChatID%",$TgBot->GetChatID(),$str);
	$str = str_replace("%ChatName%",$TgBot->GetChatName(),$str);
	$str = str_replace("%GetRealName%",GetClientName($TgBot->GetUserID()),$str);
	return $str;
}


function FormText()
{  
  foreach(func_get_args() as $args)
  {
    $items[] = '"'.$args.'"';
  }
  eval('$res = sprintf('.implode(',',$items).');');
  return $res;
}


function GetHashtag($str)
{
	$result = array();
	preg_match_all('/(?<!\w)#\S+/', $str, $matches);
	if(isset($matches[0]) and !empty($matches[0]))
	{
		$result = $matches[0];
	}
	return json_encode($result,JSON_UNESCAPED_UNICODE);
}


function WriteAction($userid="",$action="",$data="",$other="")
{
	global $DBCache;
	global $SQL;
	global $TgBot;
@ $dbls = $SQL->StatusDB();
if(!$dbls)
	@ $SQL->InitDB();
	if(isset($userid) and !empty($userid))
	{
		$user = $userid;
	}
	else
	{
		$user = $TgBot->GetUserID();
	}
if(!isset($other) or empty($other))
	$other = time();
@ $resz =  $SQL->SelectDB('*','Actions','tgid','=',$user,1);
if(isset($resz) and !empty($resz))
{
	 $SQL->UpdateDB('Actions','tgid','=',$user,array('action'=>$action,'other'=>$other));
	if(!$dbls)
		@  $SQL->CloseDB();
	return;
}
else
{
	@ $resu =  $SQL->SelectDB('*','Robot','tgid','=',$user,1);
	if(isset($resu) and !empty($resu))
	{
		 $SQL->InsertDB('Actions',array('tgid'=>$user,'action'=>$action,'other'=>$other));
		if(!$dbls)
			@  $SQL->CloseDB();
		return;
	}
	if(!$dbls)
		@  $SQL->CloseDB();
	return;
}
if(!$dbls)
	@  $SQL->CloseDB();
return;
}


function ReadAction($userid="")
{
	global $DBCache;
	global $SQL;
	global $TgBot;
@ $dbls = $SQL->StatusDB();
if(!$dbls)
	@ $SQL->InitDB();
if(isset($userid) and !empty($userid))
{
	$user = $userid;
}
else
{
	$user = $TgBot->GetUserID();
}
@ $resz = $SQL->SelectDB('*','Actions','tgid','=',$user,1);
if(!$dbls)
	@ $SQL->CloseDB();
if(isset($resz) and !empty($resz))
	return $resz[0]['action'];
return null;
}


function GetClientName($userid="")
{
	global $DBCache;
	global $SQL;
	global $TgBot;
@ $dbls = $SQL->StatusDB();
if(!$dbls)
	@ $SQL->InitDB();
if(isset($userid) and !empty($userid))
	$user = $userid;
else
	$user = $TgBot->GetUserID();
@ $realname = "";
$gender = "";
@ $resx = $SQL->SelectDB('*','Robot','tgid','=',$user);
if(isset($resx[0]['realname']) and !empty($resx[0]['realname']))
$realname = $resx[0]['realname'];
else
$realname = $resx[0]['name']." ".$resx[0]['family'];
if(isset($resx[0]['gender']) and !empty($resx[0]['gender']))
{
$gender = $resx[0]['gender'];
if($gender == "male" or strtolower($gender) == "m")
{
	$gender = "جناب آقای";
}
else if($gender == "female" or strtolower($gender) == "f")
{
	$gender = "سرکار خانم";
}
}
if(!$dbls)
	@ $SQL->CloseDB();
return $gender." ".$realname;
}
?>