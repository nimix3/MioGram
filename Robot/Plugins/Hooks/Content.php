<?php
$Action = function($TgBot,$SQL,$DB,$DNA,$LOG,$LANG,$DATA)
{
	$KBD = parse_ini_file($DNA->GetStorageDir()."/KBD.db");
	$keyboard = array(array($KBD['button_main_l1'],$KBD['button_main_r1']),array($KBD['button_main_l2'],$KBD['button_main_r2']),array($KBD['button_main_l3'],$KBD['button_main_r3']));
	switch ($TgBot->GetMessage()) {
	case ($KBD['button_main_r1']):
		$resx = $SQL->SelectDB('*','Contents','type','=','"PREMIUM"',"0,7");
		if(isset($resx) and !empty($resx))
		{
			$output = "";
			foreach($resx as $dtx)
			{
				$output .= '🔸 '.$dtx['subject'].PHP_EOL;
				$output .= 'دریافت👈 '.$DATA['command_get_free'].$dtx['fid']."  👉دریافت  ".PHP_EOL.PHP_EOL;
			}
			$kbx[] = array($TgBot->InlineKeyboardButton($DATA['button_next'],"PPAGE_1"));
			$resz = $SQL->SelectDB('*','Contents','type','=','"PREMIUM"',"8,14");
			if(isset($resz) and !empty($resz))
			{
				$TgBot->SetInlineKeyboard($TgBot->GetUserID(),FormText($DATA['premium_head_template']).PHP_EOL.PHP_EOL.$output.PHP_EOL.PHP_EOL.$DATA['premium_foot_template'],$kbx);
			}
			else
			{
				$TgBot->SetInlineKeyboard($TgBot->GetUserID(),FormText($DATA['premium_head_template']).PHP_EOL.PHP_EOL.$output.PHP_EOL.PHP_EOL.$DATA['premium_foot_template'],'');
			}
		}
		else
		{
			$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['nothing_premium_content']),$keyboard);
		}
		return true; //exit//
        break;
	case ($KBD['button_main_l1']):
        $resx = $SQL->SelectDB('*','Contents','type','=','"FREE" ORDER BY RAND() ',"0,7");
		if(isset($resx) and !empty($resx))
		{
			$output = "";
			foreach($resx as $dtx)
			{
				$output .= '🔸 '.$dtx['subject'].PHP_EOL;
				$output .= 'دریافت👈 '.$DATA['command_get_free'].$dtx['fid']."  👉دریافت  ".PHP_EOL.PHP_EOL;
			}
			
			for($i=1;$i<=8;$i+=2)
			{
				$resz = $SQL->SelectDB('*','Contents','type','=','"FREE"  AND `category` = '.$i);
				if(isset($resz) and !empty($resz))
				{
					$kbx[] = array($TgBot->InlineKeyboardButton($DATA['cat'.($i)],"FPAGE_0_".($i)),$TgBot->InlineKeyboardButton($DATA['cat'.($i+1)],"FPAGE_0_".($i+1)));
				}
			}
			$kbx[] = array($TgBot->InlineKeyboardButton($DATA['button_rand_free'],"RAND_FREE"));
			$TgBot->SetInlineKeyboard($TgBot->GetUserID(),FormText($DATA['free_head_template']).PHP_EOL.PHP_EOL.$output.PHP_EOL.PHP_EOL.$DATA['free_foot_template'],$kbx);
		}
		else
		{
			$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['nothing_free_content']),$keyboard);
		}
		return true; //exit//
        break;
    default:
		$act = ReadAction();
        if(strpos($TgBot->GetMessage(),$DATA['command_get_free']) !== false or (is_numeric($TgBot->GetMessage()) and empty($act)))
		{
			if(strpos($TgBot->GetMessage(),$DATA['command_get_free']) !== false)
				$filid = intval(str_replace($DATA['command_get_free'],"",$TgBot->GetMessage()));
			else
				$filid = intval($TgBot->GetMessage());
			$resx = $SQL->SelectDB('*','Contents','fid','=',$filid);
			if(isset($resx) and !empty($resx))
			{
				if($resx[0]['type'] == "FREE")
				{
					$cover = '<a href="'.$DNA->GetWebsiteUrl().str_replace("..","",$resx[0]['cover']).'">​​</a>';
					$TgBot->SendMessage('message', $TgBot->GetUserID(), FormText($DATA['free_download_template'],$resx[0]['fid'],$resx[0]['subject'],$resx[0]['description'],'https://t.me/'.$DNA->GetBotUsername().'?start='.$TgBot->GetUserID()).PHP_EOL.trim($cover,'/'));
					$TgBot->SendMessage('message', $TgBot->GetUserID(), $resx[0]['file']);
					$SQL->UpdateDB('Contents','fid','=',$resx[0]['fid'],array('dlcount'=>intval($resx[0]['dlcount'])+1));
				}
				else
				{
					$users = json_decode($resx[0]['users'],true);
					if(is_array($users))
					{
						if(in_array($TgBot->GetUserID(),$users))
						{
							$TgBot->SendMessage('message', $TgBot->GetUserID(), FormText($DATA['premium_download_template'],$resx[0]['fid'],$resx[0]['subject'],$resx[0]['description']));
							$files = explode("::",$resx[0]['file']);
							if(is_array($files))
							{
								foreach($files as $tfile)
								{
									//$furl = $DNA->GetWebsiteUrl()."Robot/".$DNA->GetStorageDir()."/".$DATA['content_dir']."/".$tfile;
									//$furl = $DNA->GetStorageDir()."/".$DATA['content_dir']."/".$tfile;
									//$res = $TgBot->SendFile('document', $TgBot->GetUserID(), $furl,$resx[0]['subject'].PHP_EOL.$DATA['download_file_txt']);
									$TgBot->SendMessage('message', $TgBot->GetUserID(), '<a href="'.$tfile.'">​​</a>'.PHP_EOL.$DATA['download_file_txt']);
									//$LOG->LogMessage(100,"att","none",$res);
								}
							}
							$SQL->UpdateDB('Contents','fid','=',$resx[0]['fid'],array('dlcount'=>intval($resx[0]['dlcount'])+1));
							return true; //exit//
						}
						else
						{
							if(intval($DB['score']) >= intval($DATA['score_need']))
							{
								$kbx[] = array($TgBot->InlineKeyboardButton($DATA['button_yes'],"YES_".$filid),$TgBot->InlineKeyboardButton($DATA['button_no'],"NO_0"));
								$cover = '<a href="'.$DNA->GetWebsiteUrl().str_replace("..","",$resx[0]['cover']).'">​​</a>';
								$TgBot->SetInlineKeyboard($TgBot->GetUserID(),$cover.PHP_EOL.FormText($DATA['pay_score']),$kbx);
								return true; //exit//
							}
							else
							{
								$kbz[] = array($TgBot->InlineKeyboardButton($KBD['button_invite'],"",""," "));
								$cover = '<a href="'.$DNA->GetWebsiteUrl().str_replace("..","",$resx[0]['cover']).'">​​</a>';
								$TgBot->SendMessage('message', $TgBot->GetUserID(), $cover.PHP_EOL.$resx[0]['description']);
								$TgBot->SetInlineKeyboard($TgBot->GetUserID(),$cover.PHP_EOL.FormText($DATA['not_enough_score'],$resx[0]['fid'],$resx[0]['subject'],$resx[0]['description']),$kbz);
								return true; //exit//
							}
						}
					}
					else
					{
						//$LOG->LogMessage(100,"att","none","*");
						if(intval($DB['score']) >= intval($DATA['score_need']))
						{
							$kbx[] = array($TgBot->InlineKeyboardButton($DATA['button_yes'],"YES_".$filid),$TgBot->InlineKeyboardButton($DATA['button_no'],"NO_0"));
							$cover = '<a href="'.$DNA->GetWebsiteUrl().str_replace("..","",$resx[0]['cover']).'">​​</a>';
							$TgBot->SetInlineKeyboard($TgBot->GetUserID(),$cover.PHP_EOL.FormText($DATA['pay_score']),$kbx);
							return true; //exit//
						}
						else
						{
							$kbz[] = array($TgBot->InlineKeyboardButton($KBD['button_invite'],"",""," "));
							$cover = '<a href="'.$DNA->GetWebsiteUrl().str_replace("..","",$resx[0]['cover']).'">​​</a>';
							$TgBot->SendMessage('message', $TgBot->GetUserID(), $cover.PHP_EOL.$resx[0]['description']);
							$TgBot->SetInlineKeyboard($TgBot->GetUserID(),FormText($DATA['not_enough_score'],$resx[0]['fid'],$resx[0]['subject'],$resx[0]['description']),$kbz);							return true; //exit//
							return true; //exit//
						}
					}
				}
				return true; //exit//
			}
			else
			{
				$TgBot->SendMessage('message', $TgBot->GetUserID(), FormText($DATA['file_not_exist'],$filid));
				return true; //exit//
			}
		}
		if(strpos($TgBot->GetCallbackQueryData(),"RAND_FREE") !== false)
		{
			$resx = $SQL->SelectDB('*','Contents','type','=','"FREE" ORDER BY RAND()',"0,7 ");
			if(isset($resx) and !empty($resx))
			{
				$output = "";
				foreach($resx as $dtx)
				{
					$output .= '🔸 '.$dtx['subject'].PHP_EOL;
					$output .= 'دریافت👈 '.$DATA['command_get_free'].$dtx['fid']."  👉دریافت  ".PHP_EOL.PHP_EOL;
				}
				$kbx[] = array($TgBot->InlineKeyboardButton($DATA['button_rand_free'],"RAND_FREE"));
				$TgBot->EditMessage(FormText($DATA['free_head_template']).PHP_EOL.PHP_EOL.$output.PHP_EOL.PHP_EOL.FormText($DATA['free_foot_template']),$kbx,$TgBot->GetCallbackQueryMessageID(),$TgBot->GetCallbackQueryChatID());
			}
			else
			{
				$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['nothing_free_pgcontent']),$keyboard);
			}
		}
		if(strpos($TgBot->GetCallbackQueryData(),"FPAGE_") !== false)
		{
			$TgBot->AnswerCallbackQuery($TgBot->GetCallbackQueryID(),$DATA['load_fpage_msg'],false);
			$item = intval(explode("_",$TgBot->GetCallbackQueryData())[1]);
			$cat = intval(explode("_",$TgBot->GetCallbackQueryData())[2]);
			$min = ($item * 7);
			$max = 7;
			if($cat <= 0)
				$resx = $SQL->SelectDB('*','Contents','type','=','"FREE" ',$min.','.$max);
			else
				$resx = $SQL->SelectDB('*','Contents','type','=','"FREE" AND `category` = "'.$cat.'" ',$min.','.$max);
			if(isset($resx) and !empty($resx))
			{
				$output = "";
				foreach($resx as $dtx)
				{
					$output .= '🔸 '.$dtx['subject'].PHP_EOL;
					$output .= 'دریافت👈 '.$DATA['command_get_free'].$dtx['fid']."  👉دریافت  ".PHP_EOL.PHP_EOL;
				}
				$min = ($item + 1) * 7;
				$max = 7;
				if($cat <= 0)
					$resz = $SQL->SelectDB('*','Contents','type','=','"FREE" ',$min.','.$max);
				else
					$resz = $SQL->SelectDB('*','Contents','type','=','"FREE" AND `category` = "'.$cat.'" ',$min.','.$max);
				if($item > 0 and isset($resz) and !empty($resz))
					$kbx[] = array($TgBot->InlineKeyboardButton($DATA['button_back'],"FPAGE_".($item-1)."_".$cat),$TgBot->InlineKeyboardButton($DATA['button_next'],"FPAGE_".($item+1)."_".$cat));
				else if(isset($resz) and !empty($resz))
					$kbx[] = array($TgBot->InlineKeyboardButton($DATA['button_next'],"FPAGE_".($item+1)."_".$cat));
				else if($item > 0)
					$kbx[] = array($TgBot->InlineKeyboardButton($DATA['button_back'],"FPAGE_".($item-1)."_".$cat));
				$TgBot->EditMessage(FormText($DATA['free_head_template']).PHP_EOL.FormText($DATA['category_title_text'],$DATA['cat'.$cat]).PHP_EOL.PHP_EOL.$output.PHP_EOL.PHP_EOL.FormText($DATA['free_foot_template']),$kbx,$TgBot->GetCallbackQueryMessageID(),$TgBot->GetCallbackQueryChatID());
			}
			else
			{
				$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['nothing_free_pgcontent']),$keyboard);
			}
		}
		if(strpos($TgBot->GetCallbackQueryData(),"PPAGE_") !== false or strpos($TgBot->GetCallbackQueryData(),"NO_") !== false)
		{
			$TgBot->AnswerCallbackQuery($TgBot->GetCallbackQueryID(),$DATA['load_ppage_msg'],false);
			$item = intval(explode("_",$TgBot->GetCallbackQueryData())[1]);
			$min = ($item * 7);
			$max = 7;
			$resx = $SQL->SelectDB('*','Contents','type','=','"PREMIUM"',$min.','.$max);
			if(isset($resx) and !empty($resx))
			{
				$output = "";
				foreach($resx as $dtx)
				{
					$output .= '🔸 '.$dtx['subject'].PHP_EOL;
					$output .= 'دریافت👈 '.$DATA['command_get_free'].$dtx['fid']."  👉دریافت  ".PHP_EOL.PHP_EOL;
				}
				$min = ($item + 1) * 7;
				$max = 7;
				$resz = $SQL->SelectDB('*','Contents','type','=','"PREMIUM"',$min.','.$max);
				if($item > 0 and isset($resz) and !empty($resz))
					$kbx[] = array($TgBot->InlineKeyboardButton($DATA['button_back'],"PPAGE_".$item-1),$TgBot->InlineKeyboardButton($DATA['button_next'],"PPAGE_".$item+1));
				else if(isset($resz) and !empty($resz))
					$kbx[] = array($TgBot->InlineKeyboardButton($DATA['button_next'],"PPAGE_".$item+1));
				else if($item > 0)
					$kbx[] = array($TgBot->InlineKeyboardButton($DATA['button_back'],"PPAGE_".$item-1));
				$TgBot->EditMessage(FormText($DATA['premium_head_template']).PHP_EOL.PHP_EOL.$output.PHP_EOL.PHP_EOL.FormText($DATA['premium_foot_template']),$kbx,$TgBot->GetCallbackQueryMessageID(),$TgBot->GetCallbackQueryChatID());
			}
			else
			{
				$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['nothing_premium_pgcontent']),$keyboard);
			}
		}
		if(strpos($TgBot->GetCallbackQueryData(),"YES_") !== false)
		{
			$TgBot->AnswerCallbackQuery($TgBot->GetCallbackQueryID(),$DATA['load_ppage_msg'],false);
			$item = intval(explode("_",$TgBot->GetCallbackQueryData())[1]);
			$resx = $SQL->SelectDB('*','Contents','fid','=',$item.' AND `type` = "PREMIUM"');
			if(isset($resx) and !empty($resx))
			{
				if(intval($DB['score']) >= intval($DATA['score_need']) and strpos($TgBot->GetCallbackQueryData(),"YES_") !== false)
				{
					$users = json_decode($resx[0]['users'],true);
					$users[] = $TgBot->GetUserID();
					$users = array_unique($users);
					$SQL->UpdateDB('Contents','fid','=',$resx[0]['fid'],array('users'=>json_encode($users),'dlcount'=>intval($resx[0]['dlcount'])+1));
					$SQL->UpdateDB('Robot','tgid','=',$TgBot->GetUserID(),array('score'=>intval(intval($DB['score']) - intval($DATA['score_need']))));
					//$TgBot->SendMessage('message', $TgBot->GetUserID(), FormText($DATA['premium_download_template'],$resx[0]['fid'],$resx[0]['subject'],$resx[0]['description'],$DNA->GetWebsiteUrl()."Robot/".$DNA->GetStorageDir()."/".$DATA['content_dir']."/".$resx[0]['file']));
					$TgBot->EditMessage(FormText($DATA['premium_template'],$resx[0]['fid'],$resx[0]['subject'],$resx[0]['description']),$kbx,$TgBot->GetCallbackQueryMessageID(),$TgBot->GetCallbackQueryChatID());
					$files = explode("::",$resx[0]['file']);
					if(is_array($files))
					{
						foreach($files as $tfile)
						{
							$TgBot->SendMessage('message', $TgBot->GetUserID(), '<a href="'.$tfile.'">​​</a>'.PHP_EOL.$DATA['download_file_txt']);
						}
					}
				}
			}
			else
			{
				$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['nothing_premium_pgcontent']),$keyboard);
			}
		}
		return true; //exit//
        break;
	}
};


$PreRequirement = function($BotObj,$DBObj,$DNA,$Logger,$DataLoad)
{
	return;
};
?>