<?php
$Action = function($TgBot,$SQL,$DB,$DNA,$LOG,$LANG,$DATA)
{
	$KBD = parse_ini_file($DNA->GetStorageDir()."/KBD.db");
	$keyboard = array(array($KBD['button_main_l1'],$KBD['button_main_r1']),array($KBD['button_main_l2'],$KBD['button_main_r2']),array($KBD['button_main_l3'],$KBD['button_main_r3']));
	$keyboard2 = array(array($KBD['button_profile_clients'],$KBD['button_contact_us']),array($KBD['button_invite_friends'],$KBD['button_help_bot']),array($KBD['button_back_to_main']));
	switch ($TgBot->GetMessage()) {
	case ($KBD['button_main_r2']):
        $TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['help_contact_msg']),$keyboard2);
		return true; //exit//
        break;
	case ("/invite"):
		$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['help_invite_msg'],GetClientName($TgBot->GetUserID()),'https://t.me/'.$DNA->GetBotUsername().'?start='.$TgBot->GetUserID()),$keyboard2);
		$kb[] = array($TgBot->InlineKeyboardButton($KBD['button_invite'],"",""," "));
		$TgBot->SetInlineKeyboard($TgBot->GetUserID(),FormText($DATA['user_invite_msg'],'https://t.me/'.$DNA->GetBotUsername().'?start='.$TgBot->GetUserID()),$kb);
		return true; //exit//
        break;
    case ($KBD['button_invite_friends']):
		$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['help_invite_msg'],GetClientName($TgBot->GetUserID()),'https://t.me/'.$DNA->GetBotUsername().'?start='.$TgBot->GetUserID()),$keyboard2);
		$kb[] = array($TgBot->InlineKeyboardButton($KBD['button_invite'],"",""," "));
		$TgBot->SetInlineKeyboard($TgBot->GetUserID(),FormText($DATA['user_invite_msg'],'https://t.me/'.$DNA->GetBotUsername().'?start='.$TgBot->GetUserID()),$kb);
		return true; //exit//
        break;
    case ($KBD['button_contact_us']):
		if(isset($DB['phone']) and !empty($DB['phone']))
		{
			$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['contact_us_msg']),array(array($KBD['button_discard'])));
			WriteAction($TgBot->GetUserID(),"Q");
			return true; //exit//
		}
		else
		{
			$sharebtn = $TgBot->KeyboardButton($KBD['button_share_phone'],true,false);
			$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['share_phone_msg'],GetClientName($DB['tgid'])),array(array($sharebtn),array($KBD['button_discard'])));
			WriteAction($TgBot->GetUserID(),"P");
			return true; //exit//
		}
        break;
	case ($KBD['button_help_bot']):
        $TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['help_view_msg']),$keyboard2);
        break;
	case ($KBD['button_profile_clients']):
        if(isset($DB) and !empty($DB))
		{
			$realname = $DB['realname'];
			$uid = $DB['uid'];
			$tgid = $DB['tgid'];
			$username = $DB['username'];
			$gender = $DB['gender'];
			$email = $DB['email'];
			$phone = $DB['phone'];
			$birth = $DB['birth'];
			$reagent = $DB['reagent'];
			$subset = count($DB['subset']);
			$stime = $DB['stime'];
			$score = intval($DB['score']);
			$lastuse = $DB['lastuse'];
			$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['profile_mask_msg'],$uid,$tgid,$username,$phone,$score,$subset,jdate('H:i:s ,Y/n/j',$stime),jdate('H:i:s ,Y/n/j',$lastuse)),$keyboard2);
		}
        break;
    case ($KBD['button_back_to_main']):
        $TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['back_to_main_msg']),$keyboard);
		return true; //exit//
        break;
	case ($KBD['button_discard']):
		$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['discard_msg']),$keyboard);
		WriteAction($TgBot->GetUserID(),"");
		return true; //exit//
		break;
    default:
        return;
	}
};


$PreRequirement = function($BotObj,$DBObj,$DNA,$Logger,$DataLoad)
{
	return;
};
?>