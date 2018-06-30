<?php
$Action = function($TgBot,$SQL,$DB,$DNA,$LOG,$LANG,$DATA)
{
	$KBD = parse_ini_file($DNA->GetStorageDir()."/KBD.db");
	$keyboard = array(array($KBD['button_main_l1'],$KBD['button_main_r1']),array($KBD['button_main_l2'],$KBD['button_main_r2']),array($KBD['button_main_l3'],$KBD['button_main_r3']));
	$keyboard2 = array(array($KBD['button_introduce'],$KBD['button_survey']),array($KBD['button_coupon'],$KBD['button_app']),array($KBD['button_back_to_main']));
	$keyboard3 = array(array($KBD['button_discard']));
	switch ($TgBot->GetMessage()) {
	case ($KBD['button_main_l2']):
        $TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['quantum_part_msg']),$keyboard2);
		return true; //exit//
        break;
	case ($KBD['button_app']):
		$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['app_get_msg']),$keyboard2);
		return true; //exit//
		break;
	case ($KBD['button_introduce']):
		$kb[] = array($TgBot->InlineKeyboardButton($DATA['button_intro_uno'],"INTRO_1"));
		$kb[] = array($TgBot->InlineKeyboardButton($DATA['button_intro_due'],"INTRO_2"));
		$TgBot->SetInlineKeyboard($TgBot->GetUserID(),FormText($DATA['introduce_intro_msg']),$kb);
		return true; //exit//
        break;
	case ($KBD['button_coupon']):
		if(!isset($DB['realname']) or empty($DB['realname']))
		{
			$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['tell_realname_msg']),$keyboard3);
			WriteAction($TgBot->GetUserID(),"Qr");
			return true; //exit//
		}
		$score = intval($DB['score']);
		$invites = intval(count(json_decode($DB['subset'],true)));
		$resx = $SQL->SelectDB('*','Coupons','tgid','=',$TgBot->GetUserID());
		if(intval($resx[0]['time']) + 2592000 > time())
		{
			if(file_exists($DNA->GetStorageDir()."/".$DATA['coupon_directory']."/".$resx[0]['cid'].".jpg"))
			{
				$TgBot->SendFile('photo', $TgBot->GetUserID(), $DNA->GetStorageDir()."/".$DATA['coupon_directory']."/".$resx[0]['cid'].".jpg", FormText($DATA['coupon_comment'],$TgBot->GetUserID()));
			}
		}
		else
		{
			if($invites >= $DATA['coupon_invite_1'])
			{
				if($invites >= $DATA['coupon_invite_3'])
				{
					$CinV = intval($DATA['coupon_percent_3']);
					$Cc = intval($DATA['coupon_invite_3']);
				}
				else if($invites >= $DATA['coupon_invite_2'])
				{
					$CinV = intval($DATA['coupon_percent_2']);
					$Cc = intval($DATA['coupon_invite_2']);
				}
				else
				{
					$CinV = intval($DATA['coupon_percent_1']);
					$Cc = intval($DATA['coupon_invite_1']);
				}
				$cid = GenerateID(14);
				if($SQL->InsertDB('Coupons',array('cid'=>$cid,'tgid'=>$TgBot->GetUserID(),'time'=>time(),'data'=>$CinV)))
				{
					//$SQL->UpdateDB('Robot','tgid','=',$TgBot->GetUserID(),array('score'=>intval($score - intval($DATA['coupon_score']))));
					$MText = new MagicText();
					$source = $DNA->GetStorageDir()."/".$DATA['coupon_directory']."/c".$CinV.".jpg";
					$destination = $DNA->GetStorageDir()."/".$DATA['coupon_directory']."/".$cid.".jpg";
					$xtime = strrev(jdate('Y/n/j',time()+2592000,'','','en'));					
					try
					{
						$MText->GenerateImages($source,$destination,array(255, 255, 255),$DNA->GetAssetsDir()."/font/byekan.ttf",$DB['uid'],13,533,235,true,18,0);
						$MText->GenerateImages($destination,$destination,array(255, 255, 255),$DNA->GetAssetsDir()."/font/byekan.ttf",$DB['realname'],16,533,271,true,18,0);
						$MText->GenerateImages($destination,$destination,array(255, 255, 255),$DNA->GetAssetsDir()."/font/byekan.ttf",$xtime,16,533,309,true,18,0);
					}
					catch(Exception $ex)
					{
						$LOG->LogMessage(101,'warning','coupon',$ex->getMessage());
					}
					$TgBot->SendFile('photo', $TgBot->GetUserID(), $DNA->GetStorageDir()."/".$DATA['coupon_directory']."/".$cid.".jpg",FormText($DATA['coupon_comment'],$TgBot->GetUserID()));
					$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['coupon_generated_msg']),$keyboard2);
				}
			}
			else
			{
				$kbz[] = array($TgBot->InlineKeyboardButton($KBD['button_invite'],"",""," "));
				$TgBot->SetInlineKeyboard($TgBot->GetUserID(),$cover.PHP_EOL.FormText($DATA['not_enough_invite'],$resx[0]['fid'],$resx[0]['subject'],$resx[0]['description']),$kbz);
			}
		}
		return true; //exit//
		break;
    default:
		if(strpos($TgBot->GetCallbackQueryData(),"INTRO_") !== false)
		{
			$TgBot->AnswerCallbackQuery($TgBot->GetCallbackQueryID(),$DATA['load_intro_msg'],false);
			$item = intval(explode("_",$TgBot->GetCallbackQueryData())[1]);
			if(intval($item) == 1)
				$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['introduce_uno_msg']),$keyboard2);
			if(intval($item) == 2)
				$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['introduce_due_msg']),$keyboard2);
		}
		if(ReadAction() == "Qr")
		{	
			$rname = $SQL->SecureDBQuery($TgBot->GetMessage(),true);
			if(isset($rname) and !empty($rname))
			{
				$SQL->UpdateDB('Robot','tgid','=',$TgBot->GetUserID(),array('realname'=>$rname));
				$TgBot->SetKeyboard($TgBot->GetUserID(),FormText($DATA['got_realname_msg'],GetClientName($TgBot->GetUserID())),$keyboard2);
			}
			WriteAction($TgBot->GetUserID(),"");
			return true; //exit//
		}
        return;
	}
};


$PreRequirement = function($BotObj,$DBObj,$DNA,$Logger,$DataLoad)
{
	return;
};
?>