<?php
$Action = function($TgBot,$SQL,$DB,$DNA,$LOG,$LANG,$DATA)
{
	$KBD = parse_ini_file($DNA->GetStorageDir()."/KBD.db");
	if(file_exists($DNA->GetStorageDir()."/surveys.txt"))
	{
		$SurvData = file_get_contents($DNA->GetStorageDir()."/surveys.txt");
		if(isset($SurvData) and !empty($SurvData))
		{
			$SurvData = explode('***',$SurvData);
		}
	}
	$keyboard = array(array($KBD['button_main_l1'],$KBD['button_main_r1']),array($KBD['button_main_l2'],$KBD['button_main_r2']),array($KBD['button_main_l3'],$KBD['button_main_r3']));
	$keyboard2 = array(array($KBD['button_profile_clients'],$KBD['button_contact_us']),array($KBD['button_invite_friends'],$KBD['button_help_bot']),array($KBD['button_back_to_main']));
	switch ($TgBot->GetMessage()) {
	case ($KBD['button_survey']):
		$kb[] = array($TgBot->InlineKeyboardButton($DATA['button_next'],"SURVEY_1"));
		$TgBot->SetInlineKeyboard($TgBot->GetUserID(),FormText($SurvData[0]),$kb);
		return true; //exit//
        break;
    default:
		if(strpos($TgBot->GetCallbackQueryData(),"SURVEY_") !== false)
		{
			if(file_exists($DNA->GetStorageDir()."/surveys.txt"))
			{
				$SurvData = file_get_contents($DNA->GetStorageDir()."/surveys.txt");
				if(isset($SurvData) and !empty($SurvData))
				{
					$SurvData = explode('***',$SurvData);
				}
			}
			$item = intval(explode("_",$TgBot->GetCallbackQueryData())[1]);
			if(isset($SurvData[$item]) and !empty($SurvData[$item]))
			{
				$btn = array();
				if(isset($SurvData[$item-1]) and !empty($SurvData[$item-1]))
					$btn[] = $TgBot->InlineKeyboardButton($DATA['button_back'],"SURVEY_".($item-1));
				if(isset($SurvData[$item+1]) and !empty($SurvData[$item+1]))
					$btn[] = $TgBot->InlineKeyboardButton($DATA['button_next'],"SURVEY_".($item+1));
				$kb[] = $btn;
				$TgBot->AnswerCallbackQuery($TgBot->GetCallbackQueryID(),$DATA['load_survey_msg'],false);
				$TgBot->EditMessage(FormText($SurvData[$item]),$kb,$TgBot->GetCallbackQueryMessageID(),$TgBot->GetCallbackQueryChatID());
				return true; //exit//
			}
			else
			{
				$TgBot->AnswerCallbackQuery($TgBot->GetCallbackQueryID(),$DATA['loaderr_survey_msg'],false);
				return true; //exit//
			}
		}
        return;
	}
};







$PreRequirement = function($BotObj,$DBObj,$DNA,$Logger,$DataLoad)
{
	return;
};
?>