<?php
$Action = function($TgBot,$SQL,$DB,$DNA,$LOG,$LANG,$DATA)
{
	/////////////////////////////////////////////////////////////////////////////////
	/// STARTING BOT
	$KBD = parse_ini_file($DNA->GetStorageDir()."/KBD.db");
	$keyboard = array(array($KBD['button_main_l1'],$KBD['button_main_r1']),array($KBD['button_main_l2'],$KBD['button_main_r2']),array($KBD['button_main_l3'],$KBD['button_main_r3']));
	if(strpos($TgBot->GetMessage(),"/start") !== false)
	{
		$chatid = $TgBot->GetChatID();
		if(isset($chatid) and !empty($chatid))
			if($TgBot->GetChatType() == "group" or $TgBot->GetChatType() == "supergroup")
			{
					@ $usercode = str_replace("/start@xxxxbot","",$TgBot->GetMessage());
					@ $usercode = str_replace("/startgroup","",$usercode);
					@ $usercode = str_replace("/start","",$usercode);
					@ $usercode = str_replace(" ","",$usercode);
			
					if(!isset($usercode) or empty($usercode))
						$usercode = tgid2uid($TgBot->GetUserID());
					
					$resu = $SQL->SelectDB('*','Robot','uid','= "',$usercode.'"');
					if(isset($resu) and !empty($resu))
					{
						$resx = $SQL->SelectDB('*','Groups','groupid','= "',$chatid.'"');
						if(!isset($resx[0]['referer']) or empty($resx[0]['referer']))
						{
							$datax = array();
							$datax = json_decode($resu[0]['groups'],true);
							$datax[] = $chatid;
							
							$SQL->UpdateDB('Robot','uid','= "',$usercode.'"',array('groups'=>json_encode(array_unique($datax))));
							$SQL->UpdateDB('Groups','groupid','= "',$chatid.'"',array('referer'=>$usercode));
							
							$TgBot->SendMessage('message',$usercode,GetClientName($usercode).FormText($DATA['invite_group'],intval(count($data))));
							$TgBot->SendMessage('message',$chatid,FormText($DATA['invite_gpadmin'],GetClientName($usertgid)));
						}
					}
				return true; //exit//
			}
			
			@ $codebazar = str_replace("/start","",$TgBot->GetMessage());
			@ $codebazar = str_replace(" ","",$codebazar);
			if(!isset($codebazar) or empty($codebazar))
			{
				$codebazar = null;
			}
			
			if(isset($DB) and !empty($DB))
			{
				$SQL->UpdateDB('Robot','tgid','=',$TgBot->GetUserID(),array('username'=>$TgBot->GetUsername(),'name'=>$TgBot->GetFirstName(),'family'=>$TgBot->GetLastName(),'lastuse'=>time(),'active'=>'1'));
				$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['return_bot']),$keyboard);
			}
			else
			{
				//$postsend = json_encode(array((time()+86400)=>base64_encode('متن1'),(time()+172800)=>base64_encode('متن2'),(time()+600)=>base64_encode('متن3')));
				$postsend = "";
				$SQL->InsertDB('Robot',array('uid'=>GenerateID(16),'tgid'=>$TgBot->GetUserID(),'username'=>$TgBot->GetUsername(),'name'=>$TgBot->GetFirstName(),'family'=>$TgBot->GetLastName(),'active'=>'1','stime'=>time(),'lastuse'=>time(),'reagent'=>$codebazar,'history'=>$postsend));
				if(isset($codebazar) and !empty($codebazar))
				{
					$resx = $SQL->SelectDB('*','Robot','uid','=',' "'.$codebazar.'" OR `tgid` = "'.$codebazar.'" ');
					if(isset($resx) and !empty($resx))
					{
						$subset = json_decode($resx[0]['subset'],true);
						$subset[] = $TgBot->GetUserID();
						$SQL->UpdateDB('Robot','uid','=',' "'.$codebazar.'" OR `tgid` = "'.$codebazar.'" ',array('subset'=>json_encode($subset),'score'=>intval($resx[0]['score'])+intval($DATA['invite_score'])));
					
						if(isset($resx[0]['score']))
							if(intval($resx[0]['score']) < intval($DATA['max_notify_score']))
							{
								if(intval($resx[0]['score']) == intval($DATA['first_notify_score']))
									$TgBot->SendMessage('message', $codebazar,GetClientName($resx[0]['tgid']).FormText($DATA['first_notify_msg']));
								else if(intval($resx[0]['score']) == intval($DATA['second_notify_score']))
									$TgBot->SendMessage('message', $codebazar,GetClientName($resx[0]['tgid']).FormText($DATA['second_notify_msg']));
								else if(intval($resx[0]['score']) == intval($DATA['third_notify_score']))
									$TgBot->SendMessage('message', $codebazar,GetClientName($resx[0]['tgid']).FormText($DATA['third_notify_msg']));
								else if(intval($resx[0]['score']) == intval($DATA['fourth_notify_score']))
									$TgBot->SendMessage('message', $codebazar,GetClientName($resx[0]['tgid']).FormText($DATA['fourth_notify_msg']));
								else
									$TgBot->SendMessage('message', $codebazar,GetClientName($resx[0]['tgid']).FormText($DATA['notify_score_msg']),$TgBot->GetFirstName(),$TgBot->GetLastName(),(intval($resx[0]['score']+$DATA['invite_score'])));
							}
					}
				}
				$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['start_bot']),$keyboard);
			}
		return true; //exit//
	}
	/////////////////////////////////////////////////////////////////////////////////
	/// GROUP OPERATOR
	$chatid = $TgBot->GetChatID();
	if(isset($chatid) and !empty($chatid))
	if($TgBot->GetChatType() == "group" or $TgBot->GetChatType() == "supergroup")
	{
		$resx = SelectDB('*','Groups','groupid','=',$chatid);
		if(!isset($resx) or empty($resx))
		{	
			$SQL->InsertDB('Groups',array('groupid'=>$chatid,'stime'=>time(),'active'=>'1','members'=>$TgBot->GetChatMemberCount($chatid),'history'=>FormText($DATA['notify_group_msg'])));
		}
		else
		{
			$SQL->UpdateDB('Groups','groupid','=','"'.$chatid.'"',array('members'=>$TgBot->GetChatMemberCount($chatid)));
		}
	}
	/////////////////////////////////////////////////////////////////////////////////
	/// INLINE SHARE
	@ $InlineID = $TgBot->GetInlineQueryID();
	@ $IData = $TgBot->GetInlineQueryData();
	@ $IUser = $TgBot->GetInlineQueryUserID();
	if(isset($InlineID) and !empty($InlineID))
	{
		$result = array();
		if($IData == "test")
		{
			$result[] = $TgBot->InitArticle("101",$DATA['invite_subj_text'],FormText($DATA['invite_desc_text'],'https://t.me/'.$DNA->GetBotUsername().'?start='.$IUser),"HTML",false,'',false,'',"https://raze4fasl.space/Robot/Assets/img/click.jpg");
		}
		else
		{
			$result[] = $TgBot->InitArticle("101",$DATA['invite_subj_text'],FormText($DATA['invite_desc_text'],'https://t.me/'.$DNA->GetBotUsername().'?start='.$IUser),"HTML",false,'',false,'',"https://raze4fasl.space/Robot/Assets/img/click.jpg");
		}
		$res = $TgBot->AnswerInlineQuery($InlineID, $result, 3600, true);
		return true; //exit//
	}
	/////////////////////////////////////////////////////////////////////////////////
	/// INITILIZE BOT
	$usertgid = $TgBot->GetUserID();
	if(isset($usertgid) and !empty($usertgid))
	{
		if(isset($DB) and !empty($DB))
		{
			$SQL->UpdateDB('Robot','tgid','=',$usertgid,array('username'=>$TgBot->GetUsername(),'name'=>$TgBot->GetFirstName(),'family'=>$TgBot->GetLastName(),'lastuse'=>time()));
			if($DB['ban'])
			{
				$TgBot->SendMessage('message',$usertgid,FormText($DATA['dear_user']).PHP_EOL.GetClientName($usertgid).FormText($DATA['access_deny'],GetClientName($usertgid)));
				return true; //exit//
			}
		}
		else
		{
			$SQL->InsertDB('Robot',array('uid'=>GenerateID(16),'tgid'=>$TgBot->GetUserID(),'username'=>$TgBot->GetUsername(),'name'=>$TgBot->GetFirstName(),'family'=>$TgBot->GetLastName(),'active'=>'1','lastuse'=>time(),'stime'=>time()));
		}
	}
	/////////////////////////////////////////////////////////////////////////////////
	/// DISCARD PRIOR
	if($KBD['button_discard'] == $TgBot->GetMessage())
	{
		$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['discard_msg']),$keyboard);
		WriteAction($TgBot->GetUserID(),"");
		return true; //exit//
	}
	/////////////////////////////////////////////////////////////////////////////////
	/// CHAT in BOT
	if(ReadAction() == "Q")
	{
		@ $fileid = $TgBot->GetFileID();
		if(isset($fileid) and !empty($fileid))
		{
			$message = "FILE::".$fileid;
		}
		else
		$message = $SQL->SecureDBQuery($TgBot->GetMessage(),true);
		$SQL->InsertDB('Chats',array('chatid'=>$TgBot->GetMessageID(),'xfrom'=>$TgBot->GetUserID(),'xto'=>'ADMIN','time'=>time(),'message'=>$message,'seen'=>'0','tags'=>GetHashtag($message)));
		$TgBot->SetKeyboard($TgBot->GetUserID(),$DATA['message_sent'],$keyboard);
		if($DATA['inbot_message'] == 1)
		{
			$TgBot->ForwardMessage($DATA['admin_id'], $TgBot->GetChatID(), $TgBot->GetMessageID(), false);
			$TgBot->SendMessage('message', $DATA['admin_id'], "☝️ شناسه کاربر فوق  : /".$TgBot->GetUserID());
		}
		WriteAction($TgBot->GetUserID(),"");
		return true; //exit//
	}
	/////////////////////////////////////////////////////////////////////////////////
	/// SHARE PHONE
	else if(ReadAction() == "P")
	{
		$phone = $TgBot->GetContactPhone();
		if(isset($phone) and !empty($phone) and $TgBot->GetContactUserID() == $TgBot->GetUserID())
		{
			if($SQL->UpdateDB('Robot','tgid','= "',$TgBot->GetUserID().'"',array('phone'=>$phone)))
			{
				$res = $TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['contact_us_msg']),array(array($KBD['button_discard'])));
				WriteAction($TgBot->GetUserID(),"Q");
				return true; //exit//
			}
			WriteAction($TgBot->GetUserID(),"");
			return true; //exit//
		}
	}
return;
};


$PreRequirement = function($BotObj,$DBObj,$DNA,$Logger,$DataLoad)
{
	return;
};
?>