<?php
/*
	CITADEL is a core library of CITADEL framework created by NIMIX3.
	CITADEL library V.4.2
	ALL RIGHTS RESERVED, PLEASE DO NOT COPY OR SELL THIS PROJECT AND OTHER RELATED MATERIALS.
*/
namespace CITADEL;
    class _xCITADEL
    {
        protected $API;
        protected $DATA;
        
        public function __construct($API,$Log=false){
        $this->API = $API;
        $this->DATA = json_decode(file_get_contents('php://input'), true);
		if($Log)
		{
			file_put_contents('log.txt',json_encode($this->DATA,JSON_UNESCAPED_UNICODE).PHP_EOL.PHP_EOL,FILE_APPEND);
		}
        }

        public function SendToChannel($type, $user, $content, $captionx='', $notify=false)
        {
            $api_key = $this->API;
            $apiendpoint = ucfirst($type);

            if ($type == "message") 
            {
                $type = 'text';
            }
            else
            {
				if ((version_compare(phpversion(), '5.5.0', '>='))) {
				$WEBSERVICE = "https://api.telegram.org/bot".$api_key."/send".$apiendpoint."?chat_id=" . $user;
				
				$post_fields = array('chat_id'   => $user,
				'caption' => $captionx,
				'parse_mode' => 'HTML',
				'disable_notification' => $notify,
				$type => new \CurlFile(realpath($content))
				);
				
				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				"Content-Type:multipart/form-data"
				));
				curl_setopt($ch, CURLOPT_URL, $WEBSERVICE); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields); 
				$output = curl_exec($ch);
				curl_close($ch);
				return $output;
				}
				else
					return "PHP >= 5.5 Needed To Execute This Method!";
			}
            $WEBSERVICE = "https://api.telegram.org/bot".$api_key."/send".$apiendpoint;
            $postData = http_build_query(array(
                'chat_id' => $user,
				'caption' => $captionx,
				'parse_mode' => 'HTML',
				'disable_notification' => $notify,
                $type => $content
            ));
            
            $context = stream_context_create(array(
                'http' => array(
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $postData
                )));
            $response = file_get_contents($WEBSERVICE, FALSE, $context);
			return $response;
        }
		
		public function SendMessage($type, $user, $content, $keyboard="", $replyid="", $web=false, $mark="HTML", $notify=false)
        {
            if(strpos($user,"@") !== false)
            {
                $this->SendToChannel($type, $user, $content);
                exit();
            }
          $api_key = $this->API;
          $apiendpoint = ucfirst($type);
		  if ($type == 'photo' || $type == "audio" || $type == "video" || $type == "document" || $type == "sticker" || $type == "contact" || $type == "location" || $type == "venue") {
			  if(strpos($content,"http://") === false and strpos($content,"https://") === false)
				$content = new \CurlFile(realpath($content),mime_content_type($content));
           } elseif ($type == "message") {
           $type = 'text';
		   }
         $ch = curl_init("https://api.telegram.org/bot".$api_key."/send".$apiendpoint);
			if ((version_compare(phpversion(), '5.5.0', '>='))) {
             curl_setopt_array($ch, array(
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_SAFE_UPLOAD => true,
             CURLOPT_POST => true,
             CURLOPT_HEADER => false,
             CURLOPT_HTTPHEADER => array(
                 'Host: api.telegram.org',
                 'Content-Type: multipart/form-data'
             ),
             CURLOPT_POSTFIELDS => array(
                 'chat_id' => $user,
				 'parse_mode' => $mark,
                 'reply_markup' => $keyboard,
                 'reply_to_message_id' => $replyid,
                 'disable_web_page_preview' => $web,
				 'disable_notification' => $notify,
                 $type => $content
             ),
             CURLOPT_TIMEOUT => 0,
             CURLOPT_CONNECTTIMEOUT => 6000,
             CURLOPT_SSL_VERIFYPEER => false
             ));
         }
         else
         {
             curl_setopt_array($ch, array(
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_POST => true,
             CURLOPT_HEADER => false,
             CURLOPT_HTTPHEADER => array(
                 'Host: api.telegram.org',
                 'Content-Type: multipart/form-data'
             ),
             CURLOPT_POSTFIELDS => array(
                 'chat_id' => $user,
				 'parse_mode' => $mark,
                 'reply_markup' => $keyboard,
                 'reply_to_message_id' => $replyid,
                 'disable_web_page_preview' => $web,
				 'disable_notification' => $notify,
                 $type => $content
             ),
             CURLOPT_TIMEOUT => 0,
             CURLOPT_CONNECTTIMEOUT => 6000,
             CURLOPT_SSL_VERIFYPEER => false
             ));
         }
       $res = curl_exec($ch);
       curl_close($ch);
	   return $res;
       }

        public function SendFile($type, $user, $content, $caption="", $title="", $notify=false)
        {
			if(strpos($user,"@") !== false)
            {
                $this->SendToChannel($type, $user, $content, $caption);
                exit();
            }
			$api_key = $this->API;
			$apiendpoint = ucfirst($type);
			if ($type == 'photo' || $type == "audio" || $type == "video" || $type == "document" || $type == "sticker" || $type == "contact" || $type == "location" || $type == "venue") {
				if(strpos($content,"http://") === false and strpos($content,"https://") === false)
				$content = new \CurlFile(realpath($content),mime_content_type($content));
				} elseif ($type == "message") {
				$type = 'text';
				}
			$ch = curl_init("https://api.telegram.org/bot".$api_key."/send".$apiendpoint);
			if ((version_compare(phpversion(), '5.5.0', '>='))) {
			curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SAFE_UPLOAD => true,
			CURLOPT_POST => true,
			CURLOPT_HEADER => false,
			CURLOPT_HTTPHEADER => array(
				'Host: api.telegram.org',
				'Content-Type: multipart/form-data'
			),
			CURLOPT_POSTFIELDS => array(
				'chat_id' => $user,
				'caption'=> $caption,
				'title'=> $title,
				'parse_mode' => 'Markdown',
				'disable_notification' => $notify,
				$type => $content,
			),
			CURLOPT_TIMEOUT => 0,
			CURLOPT_CONNECTTIMEOUT => 6000,
			CURLOPT_SSL_VERIFYPEER => false
			));
			}
			else
			{
			curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POST => true,
			CURLOPT_HEADER => false,
			CURLOPT_HTTPHEADER => array(
				'Host: api.telegram.org',
				'Content-Type: multipart/form-data'
			),
			CURLOPT_POSTFIELDS => array(
				'chat_id' => $user,
				'caption'=> $caption,
				'title'=> $title,
				'parse_mode' => 'Markdown',
				'disable_notification' => $notify,
				$type => '@'.$content
			),
			CURLOPT_TIMEOUT => 0,
			CURLOPT_CONNECTTIMEOUT => 6000,
			CURLOPT_SSL_VERIFYPEER => false
			));
			}
			$res = curl_exec($ch);
			curl_close($ch);
			return $res;
        }

        public function SaveFileFromUrl($url,$dir="Data/",$hash="")
        {
			$filename = str_replace("/","",parse_url($url,PHP_URL_PATH));
			@ file_put_contents($dir.$hash.$filename,file_get_contents($url));
			if(file_exists($dir.$hash.$filename))
			{
			return $dir.$hash.$filename;
			}
			return NULL;
        }

        public function GetRawData()
        {
			return $this->DATA;
        }
		
		public function SetWebHook($url)
		{
				$res = file_get_contents("https://api.telegram.org/bot".$this->API."/setWebhook?url=".$url);
				return $res;
		}
		
		public function GetUpdates($offset,$limit,$timeout)
        {
			$api_key = $this->API;
			
			$ch = curl_init("https://api.telegram.org/bot".$api_key."/getUpdates");
			if ((version_compare(phpversion(), '5.5.0', '>='))) {
				curl_setopt_array($ch, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SAFE_UPLOAD => false,
				CURLOPT_POST => true,
				CURLOPT_HEADER => false,
				CURLOPT_HTTPHEADER => array(
				'Host: api.telegram.org',
				'Content-Type: multipart/form-data'
				),
				CURLOPT_POSTFIELDS => array(
				'offset' => intval($offset),
				'limit' => intval($limit),
				'timeout' => intval($timeout)
				),
				CURLOPT_TIMEOUT => 0,
				CURLOPT_CONNECTTIMEOUT => 6000,
				CURLOPT_SSL_VERIFYPEER => false
				));
			}
			else
			{
				curl_setopt_array($ch, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_HEADER => false,
				CURLOPT_HTTPHEADER => array(
				'Host: api.telegram.org',
				'Content-Type: multipart/form-data'
				),
				CURLOPT_POSTFIELDS => array(
				'offset' => intval($offset),
				'limit' => intval($limit),
				'timeout' => intval($timeout)
				),
				CURLOPT_TIMEOUT => 0,
				CURLOPT_CONNECTTIMEOUT => 6000,
				CURLOPT_SSL_VERIFYPEER => false
				));
			}
			$res = curl_exec($ch);
			curl_close($ch);
			return json_decode($res,true);
		}
	   
	    public function SendRawData($method,$options)
		{
			$api_key = $this->API;
			$ch = curl_init("https://api.telegram.org/bot".$api_key."/".$method);
			if ((version_compare(phpversion(), '5.5.0', '>='))) {
				curl_setopt_array($ch, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_SAFE_UPLOAD => true,
				CURLOPT_POST => true,
				CURLOPT_HEADER => false,
				CURLOPT_HTTPHEADER => array(
					'Host: api.telegram.org',
					'Content-Type: multipart/form-data'
				),
				CURLOPT_POSTFIELDS => $options,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_CONNECTTIMEOUT => 6000,
				CURLOPT_SSL_VERIFYPEER => false
				));
			}
			else
			{
				curl_setopt_array($ch, array(
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POST => true,
				CURLOPT_HEADER => false,
				CURLOPT_HTTPHEADER => array(
					'Host: api.telegram.org',
					'Content-Type: multipart/form-data'
				),
				CURLOPT_POSTFIELDS => $options,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_CONNECTTIMEOUT => 6000,
				CURLOPT_SSL_VERIFYPEER => false
				));
			}
			$res = curl_exec($ch);
			curl_close($ch);
			return $res;
		}

        public function GetChatName()
		{
			return $this->DATA["message"]["chat"]["title"];
        }

        public function GetChatID()
		{
			return $this->DATA["message"]["chat"]["id"];
        }

        public function GetChatType()
		{
			return $this->DATA["message"]["chat"]["type"];
        }

        public function GetChatUsername()
		{
			return $this->DATA["message"]["chat"]["username"];
        }
		
		public function GetRepliedMessageID()
		{
			return $this->DATA["message"]["reply_to_message"]["message_id"];
        }
		
		public function GetRepliedMessageObj()
		{
			return $this->DATA["message"]["reply_to_message"];
        }

        public function GetForwardedUserID()
		{
			return $this->DATA["message"]["forward_from"]["id"];
        }

        public function GetForwardedFirstName()
		{
			return $this->DATA["message"]["forward_from"]["first_name"];
        }

        public function GetForwardedLastName()
		{
			return $this->DATA["message"]["forward_from"]["last_name"];
        }

        public function GetForwardedUsername()
		{
			return $this->DATA["message"]["forward_from"]["username"];
        }

        public function GetContactPhone()
		{
			return $this->DATA["message"]["contact"]["phone_number"];
        }

        public function GetContactUserID()
		{
			return $this->DATA["message"]["contact"]["user_id"];
        }

        public function GetContactFirstName()
		{
			return $this->DATA["message"]["contact"]["first_name"];
        }

        public function GetContactLastName()
		{
			return $this->DATA["message"]["contact"]["last_name"];
        }

        public function GetLocationLongitude()
		{
			return $this->DATA["message"]["location"]["longitude"];
        }

        public function GetLocationLatitude()
		{
			return $this->DATA["message"]["location"]["latitude"];
        }

        public function GetNewChatAddedID()
		{
			return $this->DATA["message"]["new_chat_participant"]["id"];
        }

        public function GetNewChatAddedFirstName()
		{
			return $this->DATA["message"]["new_chat_participant"]["first_name"];
        }

        public function GetNewChatAddedLastName()
		{
			return $this->DATA["message"]["new_chat_participant"]["last_name"];
        }

        public function GetNewChatAddedUsername()
		{
			return $this->DATA["message"]["new_chat_participant"]["username"];
        }

        public function GetNewChatRemovedID()
		{
			return $this->DATA["message"]["left_chat_participant"]["id"];
        }

        public function GetNewChatRemovedFirstName()
		{
			return $this->DATA["message"]["left_chat_participant"]["first_name"];
        }

        public function GetNewChatRemovedLastName()
		{
			return $this->DATA["message"]["left_chat_participant"]["last_name"];
        }

        public function GetNewChatRemovedUsername()
		{
			return $this->DATA["message"]["left_chat_participant"]["username"];
        }

        public function GetCommand()
        {
			return explode(" ", $this->DATA["message"]["text"])[0];
        }

        public function GetCommandData()
		{
			return str_replace((explode(" ",$this->DATA["message"]["text"])[0])." ",'',$this->DATA["message"]["text"]);
        }
		
		public function GetCallbackQuery()
		{
			return $this->DATA['callback_query'];
		}
		
		public function GetCallbackQueryID()
		{
			return $this->DATA['callback_query']['id'];
		}
		
		public function GetCallbackQueryUser()
		{
			return $this->DATA['callback_query']['from'];
		}
		
		public function GetCallbackQueryMessage()
		{
			return $this->DATA['callback_query']['message']['text'];
		}
		
		public function GetCallbackQueryMessageID()
		{
			return $this->DATA['callback_query']['message']['message_id'];
		}
		
		public function GetCallbackQueryChatID()
		{
			return $this->DATA['callback_query']['message']['chat']['id'];
		}
		
		public function GetCallbackQueryUserID()
		{
			return $this->DATA['callback_query']['from']['id'];
		}
		
		public function GetCallbackQueryInlineMessageID()
		{
			return $this->DATA['callback_query']['inline_message_id'];
		}
		
		public function GetCallbackQueryData()
		{
			return $this->DATA['callback_query']['data'];
		}
		
		public function GetInlineQuery()
		{
			return $this->DATA["inline_query"];
        }
		
		public function GetInlineQueryID()
		{
			return $this->DATA["inline_query"]["id"];
        }
		
		public function GetInlineQueryData()
		{
			return $this->DATA["inline_query"]["query"];
        }
		
		public function GetInlineQueryOffset()
		{
			return $this->DATA["inline_query"]["offset"];
        }
		
		public function GetInlineQueryUserID()
		{
			return $this->DATA["inline_query"]["from"]["id"];
        }
		
		public function GetInlineQueryUsername()
		{
			return $this->DATA["inline_query"]["from"]["username"];
        }
		
		public function GetInlineQueryFirstName()
		{
			return $this->DATA["inline_query"]["from"]["first_name"];
        }
		
		public function GetInlineQueryLastName()
		{
			return $this->DATA["inline_query"]["from"]["last_name"];
        }
		
		public function GetInlineQueryResultID()
		{
			return $this->DATA["chosen_inline_result"]["result_id"];
        }
		
		public function GetInlineQueryUpdateID()
		{
			return $this->DATA["update_id"];
        }
		
		public function GetInlineQueryResult()
		{
			return $this->DATA["chosen_inline_result"];
        }
		
		public function GetInlineQueryResultData()
		{
			return $this->DATA["chosen_inline_result"]["query"];
        }
		
		public function GetInlineQueryResultUserID()
		{
			return $this->DATA["chosen_inline_result"]["from"]["id"];
        }
		
		public function GetInlineQueryResultUsername()
		{
			return $this->DATA["chosen_inline_result"]["from"]["username"];
        }
		
		public function GetInlineQueryResultFirstName()
		{
			return $this->DATA["chosen_inline_result"]["from"]["first_name"];
        }
		
		public function GetInlineQueryResultLastName()
		{
			return $this->DATA["chosen_inline_result"]["from"]["last_name"];
        }

        public function GetMessage()
        {
			return $this->DATA["message"]["text"];
        }

        public function GetUserID()
        {
			if(isset($this->DATA["message"]["from"]["id"]) and !empty($this->DATA["message"]["from"]["id"]))
				return $this->DATA["message"]["from"]["id"];
			else
				return $this->DATA["callback_query"]["from"]["id"];
        }

        public function GetUsername()
        {
			return $this->DATA["message"]["from"]["username"];
        }

        public function GetFirstName()
        {
			return $this->DATA["message"]["from"]["first_name"];
        }

        public function GetLastName()
        {
			return $this->DATA["message"]["from"]["last_name"];
        }
		
		public function GetNewChatMemberID()
        {
			return $this->DATA["message"]["new_chat_member"]['id'];
        }
		
		public function GetLeftChatMemberID()
        {
			return $this->DATA["message"]["left_chat_member"]['id'];
        }

        public function GetMessageDate()
        {
            return $this->DATA["message"]["date"];
        }

        public function GetMessageForwardedDate()
        {
            return $this->DATA["message"]["forward_date"];
        }

        public function GetMessageID()
        {
            return $this->DATA["message"]["message_id"];
        }

        public function GetMessageCaption()
        {
            return $this->DATA["message"]["caption"];
        }

        public function GetNewChatTitle()
        {
            return $this->DATA["message"]["new_chat_title"];
        }

        public function IsChatDeletePhoto()
        {
            return $this->DATA["message"]["delete_chat_photo"];
        }

        public function IsSuperGroupCreated()
        {
            return $this->DATA["message"]["supergroup_chat_created"];
        }

        public function IsGroupCreated()
        {
            return $this->DATA["message"]["group_chat_created"];
        }

        public function IsChannelCreated()
        {
            return $this->DATA["message"]["channel_chat_created"];
        }

        public function GetMigratedToSuperGroup()
        {
            return $this->DATA["message"]["migrate_to_chat_id"];
        }

        public function GetFileID()
        {
            $types = array("audio","photo","video","voice","sticker","document");
            foreach($types as $type)
            {
                if(isset($this->DATA["message"]["$type"]["file_id"]) and !empty($this->DATA["message"]["$type"]["file_id"]))
                    return $this->DATA["message"]["$type"]["file_id"];
                else if(isset(array_reverse($this->DATA["message"]["photo"])[0]["file_id"]) and !empty(array_reverse($this->DATA["message"]["photo"])[0]["file_id"]))
                {
                    return array_reverse($this->DATA["message"]["photo"])[0]["file_id"];
                }
            }
            return NULL;
        }

        public function GetFileType()
        {
            $types = array("audio","photo","video","voice","sticker","document");
            foreach($types as $type)
            {
                if(isset($this->DATA["message"]["$type"]["file_id"]) and !empty($this->DATA["message"]["$type"]["file_id"]))
                    return $type;
                else if(isset(array_reverse($this->DATA["message"]["photo"])[0]["file_id"]) and !empty(array_reverse($this->DATA["message"]["photo"])[0]["file_id"]))
                {
                    return "photo";
                }
            }
            return NULL;
        }

        public function GetFileProperty()
        {
            $types = array("audio","photo","video","voice","sticker","document");
            foreach($types as $type)
            {
                if(isset($this->DATA["message"]["$type"]["file_id"]) and !empty($this->DATA["message"]["$type"]["file_id"]))
                    return $this->DATA["message"]["$type"];
                else if(isset(array_reverse($this->DATA["message"]["photo"])[0]["file_id"]) and !empty(array_reverse($this->DATA["message"]["photo"])[0]["file_id"]))
                {
                    return $this->DATA["message"]["photo"];
                }
            }
            return NULL;
        }

        public function GetFileObject($fileid)
        {
            $api_key = $this->API;
            
            $ch = curl_init("https://api.telegram.org/bot".$api_key."/getFile");
            curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => array(
                'Host: api.telegram.org',
                'Content-Type: multipart/form-data'
            ),
            CURLOPT_POSTFIELDS => array(
                'file_id' => $fileid
            ),
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CONNECTTIMEOUT => 6000,
            CURLOPT_SSL_VERIFYPEER => false
            ));
            $res = curl_exec($ch);
            curl_close($ch);
            if(isset($res) and !empty($res))
            {
                $res = json_decode($res,true);
                return array('ID' => $res['result']['file_id'],'Size' => $res['result']['file_size'],'Path' => $res['result']['file_path']);
            }
            else
                return NULL;
        }

        public function GetProfilePhoto($userid,$offset='',$limit='')
        {
            $api_key = $this->API;
            
            $ch = curl_init("https://api.telegram.org/bot".$api_key."/getUserProfilePhotos");
            curl_setopt_array($ch, array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HEADER => false,
            CURLOPT_HTTPHEADER => array(
                'Host: api.telegram.org',
                'Content-Type: multipart/form-data'
            ),
            CURLOPT_POSTFIELDS => array(
                'user_id' => $userid,
                'offset' => $offset,
                'limit' => $limit
            ),
            CURLOPT_TIMEOUT => 0,
            CURLOPT_CONNECTTIMEOUT => 6000,
            CURLOPT_SSL_VERIFYPEER => false
            ));
            $res = curl_exec($ch);
            curl_close($ch);
            if(isset($res) and !empty($res))
            {
                $res = json_decode($res,true);
                return array('ID' => array_reverse($res['result']['photos'][0])[0]['file_id'] , 'Width' => array_reverse($res['result']['photos'][0])[0]['width'] , 'Height' => array_reverse($res['result']['photos'][0])[0]['height'] , 'Size' => array_reverse($res['result']['photos'][0])[0]['file_size']);
            }
            else
                return NULL;
        }

        public function SaveUserFile($fn,$path='',$hash='')
        {
            $fname = $this->GetFileObject($this->GetFileID())['Path'];
            $faddr = $this->SaveFileFromUrl("https://api.telegram.org/file/bot".$this->API."/$fname",$path,$hash);
            if(!isset($fn) or empty($fn))
            {
				$fn = md5(rand(1000,9999));
            }
            $ext = pathinfo($faddr, PATHINFO_EXTENSION);
            if(file_exists($faddr))
            {
                @ rename($faddr,dirname($faddr)."/".$fn.".".$ext);
                return dirname($faddr)."/".$fn.".".$ext;
            }
        }

        public function GetFileUrl($fid)
        {
            $fpath = $this->GetFileObject($fid)['Path'];
            return "https://api.telegram.org/file/bot".$this->API."/$fpath";
        }
	   
		public function EditMessage($text,$keyboard="",$msgid="",$chatid="",$inlineid="",$web=false, $mark="HTML")
		{
		   if(isset($keyboard) and !empty($keyboard))
				return $this->SendRawData('editMessageText',array('chat_id'=>$chatid,'message_id'=>$msgid,'inline_message_id'=>$inlineid,'text'=>$text,'parse_mode'=>$mark,'disable_web_page_preview'=>$web,'reply_markup'=>json_encode(array('inline_keyboard' => $keyboard))));
		   else
			    return $this->SendRawData('editMessageText',array('chat_id'=>$chatid,'message_id'=>$msgid,'inline_message_id'=>$inlineid,'text'=>$text,'parse_mode'=>$mark,'disable_web_page_preview'=>$web));
		}
	   
	   public function EditMessageCaption($text,$keyboard="",$msgid="",$chatid="",$inlineid="")
	   {
		   if(isset($keyboard) and !empty($keyboard))
			return $this->SendRawData('editMessageText',array('chat_id'=>$chatid,'message_id'=>$msgid,'inline_message_id'=>$inlineid,'caption'=>$text,'reply_markup'=>json_encode(array('inline_keyboard' => $keyboard))));
		   else
			return $this->SendRawData('editMessageText',array('chat_id'=>$chatid,'message_id'=>$msgid,'inline_message_id'=>$inlineid,'caption'=>$text));
	   }
	   
	   public function EditMessageReplyMarkup($keyboard="",$msgid="",$chatid="",$inlineid="")
	   {
			return $this->SendRawData('editMessageText',array('chat_id'=>$chatid,'message_id'=>$msgid,'inline_message_id'=>$inlineid,'reply_markup'=>json_encode(array('inline_keyboard' => $keyboard))));
	   }
	   
	   public function KickMember($chatid,$userid)
	   {
		   return $this->SendRawData('kickChatMember',array('chat_id'=>$chatid,'user_id'=>$userid));
	   }
	   
	   public function LeaveChat($chatid)
	   {
		   return $this->SendRawData('leaveChat',array('chat_id'=>$chatid));
	   }
	   
	   public function UnbanMember($chatid,$userid)
	   {
		   return $this->SendRawData('unbanChatMember',array('chat_id'=>$chatid,'user_id'=>$userid));
	   }
	   
	   public function GetChat($chatid)
	   {
		   return json_decode($this->SendRawData('getChat',array('chat_id'=>$chatid)),true);
	   }
	   
	   public function GetChatAdmin($chatid)
	   {
		   return json_decode($this->SendRawData('getChatAdministrators',array('chat_id'=>$chatid)),true);
	   }
	   
	   public function GetChatMemberCount($chatid)
	   {
		   return intval(json_decode($this->SendRawData('getChatMembersCount',array('chat_id'=>$chatid)),true)['result']);
	   }
	   
	   public function GetChatMember($chatid,$userid)
	   {
		   return json_decode($this->SendRawData('getChatMember',array('chat_id'=>$chatid,'user_id'=>$userid)),true);
	   }
	   
	   public function EditKeyboard($keyboard,$msgid="",$chatid="",$inlineid="")
	   {
		   return $this->SendRawData('editMessageReplyMarkup',array('chat_id'=>$chatid,'message_id'=>$msgid,'inline_message_id'=>$inlineid,'reply_markup'=>json_encode(array('inline_keyboard' => $keyboard))));
	   }
	   
	   public function AnswerCallbackQuery($cqid,$text,$alert=true,$cache=0)
	   {
		   return $this->SendRawData('answerCallbackQuery',array('callback_query_id'=>$cqid,'text'=>$text,'show_alert'=>$alert,'cache_time'=>$cache));
	   }
	   
	   public function SendGame($chatid,$gname,$keyboard,$notify=false,$replyto="")
	   {
		   return $this->SendRawData('sendGame',array('chat_id'=>$chatid,'game_short_name'=>$gname,'reply_markup'=>$keyboard,'disable_notification'=>$notify,'reply_to_message_id'=>$replyto));
	   }
	   
	   public function SetGameScore($userid,$score,$force=false,$diseditmsg=false,$msgid="",$chatid="",$inlineid="")
	   {
		   return $this->SendRawData('setGameScore',array('user_id'=>$userid,'score'=>$score,'chat_id'=>$chatid,'disable_edit_message'=>$diseditmsg,'force'=>$force,'message_id'=>$msgid,'inline_message_id'=>$inlineid));
	   }
	   
	   public function GetUserHighScore($userid,$msgid="",$chatid="",$inlineid="")
	   {
		  $gm = json_decode($this->SendRawData('getGameHighScores',array('user_id'=>$userid,'chat_id'=>$chatid,'message_id'=>$msgid,'inline_message_id'=>$inlineid)),true);
		  foreach($gm as $user)
		  {
			if($user['user']['id'] == $userid)
			{
				return intval($user['score']);
			}
			return NULL;
		  }
		  return NULL;
	   }
	   
	   public function SendChatAction($chatid,$action)
	   {
		   return $this->SendRawData('sendChatAction',array('chat_id'=>$chatid,'action'=>$action));
	   }
	   
		public function ForwardMessage($target, $from, $msgid, $notify=false)
        {
			$api_key = $this->API;

            $WEBSERVICE = "https://api.telegram.org/bot".$api_key."/forwardMessage";
            $postData = http_build_query(array(
			'chat_id' => $target,
			'from_chat_id' => $from,
			'message_id' => $msgid,
			'disable_notification' => $notify
            ));
            
            $context = stream_context_create(array(
			'http' => array(
			'method' => 'POST',
			'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
			'content' => $postData
			)));
            $response = file_get_contents($WEBSERVICE, FALSE, $context);
			return $response;
		}
	   
	    public function AnswerInlineQuery($ID, $result, $cache=300, $personal=false, $next="", $swchpm="", $swchparam="")
        {
          $api_key = $this->API;

         $ch = curl_init("https://api.telegram.org/bot".$api_key."/answerInlineQuery");
         if ((version_compare(phpversion(), '5.5.0', '>='))) {
             curl_setopt_array($ch, array(
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_SAFE_UPLOAD => true,
             CURLOPT_POST => true,
             CURLOPT_HEADER => false,
             CURLOPT_HTTPHEADER => array(
                 'Host: api.telegram.org',
                 'Content-Type: multipart/form-data'
             ),
             CURLOPT_POSTFIELDS => array(
                 'inline_query_id' => $ID,
				 'results' => json_encode($result),
                 'cache_time' => $cache,
                 'is_personal' => $personal,
                 'next_offset' => $next,
				 'switch_pm_text' => $swchpm,
				 'switch_pm_parameter' =>$swchparam
             ),
             CURLOPT_TIMEOUT => 0,
             CURLOPT_CONNECTTIMEOUT => 6000,
             CURLOPT_SSL_VERIFYPEER => false
             ));
         }
         else
         {
             curl_setopt_array($ch, array(
             CURLOPT_RETURNTRANSFER => true,
             CURLOPT_POST => true,
             CURLOPT_HEADER => false,
             CURLOPT_HTTPHEADER => array(
                 'Host: api.telegram.org',
                 'Content-Type: multipart/form-data'
             ),
             CURLOPT_POSTFIELDS => array(
                 'inline_query_id' => $ID,
				 'results' => json_encode($result),
                 'cache_time' => $cache,
                 'is_personal' => $personal,
                 'next_offset' => $next,
				 'switch_pm_text' => $swchpm,
				 'switch_pm_parameter' =>$swchparam
             ),
             CURLOPT_TIMEOUT => 0,
             CURLOPT_CONNECTTIMEOUT => 6000,
             CURLOPT_SSL_VERIFYPEER => false
             ));
         }
			$res = curl_exec($ch);
			curl_close($ch);
			return $res;
       }
	   
	   public function InitArticle($id,$title="",$message="",$mode="HTML",$web=true,$url="",$hideurl=false,$description="",$thumburl="",$thumbwidth=0,$thumbheight=0,$keyboard="")
	   {
		   $ArrData = array();
		   $ArrData['type']= 'article';
		   $ArrData['id']= $id;
		   $ArrData['title']= $title;
		   $ArrData['message_text']= $message;
		   $ArrData['parse_mode']= $mode;
		   $ArrData['disable_web_page_preview']= $web;
		   $ArrData['url']= $url;
		   $ArrData['hide_url']= $hideurl;
		   $ArrData['description']= $description;
		   $ArrData['thumb_url']= $thumburl;
		   $ArrData['thumb_width']= $thumbwidth;
		   $ArrData['thumb_height']= $thumbheight;
		   if(isset($keyboard) and !empty($keyboard))
			$ArrData['reply_markup']= array('inline_keyboard' => $keyboard);
		   return $ArrData;
	   }
	   
	   public function InitPhoto($id,$photourl="",$photowidth=0,$photoheight=0,$thumburl="",$title="",$description="",$caption="",$message="",$keyboard="",$mode="HTML",$web=true)
	   {
		   $ArrData = array();
		   $ArrData['type']= 'photo';
		   $ArrData['id']= $id;
		   $ArrData['photo_url']= $photourl;
		   $ArrData['photo_width']= $photowidth;
		   $ArrData['photo_height']= $photoheight;
		   $ArrData['thumb_url']= $thumburl;
		   $ArrData['title']= $title;
		   $ArrData['description']= $description;
		   $ArrData['caption']= $caption;
		   $ArrData['message_text']= $message;
		   $ArrData['parse_mode']= $mode;
		   $ArrData['disable_web_page_preview']= $web;
		   if(isset($keyboard) and !empty($keyboard))
			$ArrData['reply_markup']= array('inline_keyboard' => $keyboard);
		   return $ArrData;
	   }
	   
	   public function InitGif($id,$gifurl="",$gifwidth=0,$gifheight=0,$thumburl="",$title="",$caption="",$message="",$keyboard="",$mode="HTML",$web=true)
	   {
		   $ArrData = array();
		   $ArrData['type']= 'gif';
		   $ArrData['id']= $id;
		   $ArrData['gif_url']= $gifurl;
		   $ArrData['gif_width']= $gifwidth;
		   $ArrData['gif_height']= $gifheight;
		   $ArrData['thumb_url']= $thumburl;
		   $ArrData['title']= $title;
		   $ArrData['caption']= $caption;
		   $ArrData['message_text']= $message;
		   $ArrData['parse_mode']= $mode;
		   $ArrData['disable_web_page_preview']= $web;
		   if(isset($keyboard) and !empty($keyboard))
			$ArrData['reply_markup']= json_encode(array('inline_keyboard' => $keyboard));
		   return $ArrData;
	   }

	   public function InitMGif($id,$gifurl="",$gifwidth=0,$gifheight=0,$thumburl="",$title="",$caption="",$message="",$keyboard="",$mode="HTML",$web=true)
	   {
		   $ArrData = array();
		   $ArrData['type']= 'mpeg4_gif';
		   $ArrData['id']= $id;
		   $ArrData['mpeg4_url']= $gifurl;
		   $ArrData['mpeg4_width']= $gifwidth;
		   $ArrData['mpeg4_height']= $gifheight;
		   $ArrData['thumb_url']= $thumburl;
		   $ArrData['title']= $title;
		   $ArrData['caption']= $caption;
		   $ArrData['message_text']= $message;
		   $ArrData['parse_mode']= $mode;
		   $ArrData['disable_web_page_preview']= $web;
		   if(isset($keyboard) and !empty($keyboard))
			$ArrData['reply_markup']= array('inline_keyboard' => $keyboard);
		   return $ArrData;
	   }
	   
	   	public function InitVideo($id,$videourl="",$videowidth=0,$videoheight=0,$duration="",$mime="video/mp4",$thumburl="",$title="",$caption="",$message="",$keyboard="",$mode="HTML",$web=true)
	   {
		   $ArrData = array();
		   $ArrData['type']= 'mpeg4_gif';
		   $ArrData['id']= $id;
		   $ArrData['video_url']= $videourl;
		   $ArrData['video_width']= $videowidth;
		   $ArrData['video_height']= $videoheight;
		   $ArrData['video_duration']= $duration;
		   $ArrData['mime_type']= $mime;
		   $ArrData['thumb_url']= $thumburl;
		   $ArrData['title']= $title;
		   $ArrData['caption']= $caption;
		   $ArrData['message_text']= $message;
		   $ArrData['parse_mode']= $mode;
		   $ArrData['disable_web_page_preview']= $web;
		   if(isset($keyboard) and !empty($keyboard))
			$ArrData['reply_markup']= array('inline_keyboard' => $keyboard);
		   return $ArrData;
	   }
	   
	  	public function InitVoice($id,$voiceurl="",$duration="",$title="",$caption="",$message="",$keyboard="",$mode="HTML",$web=true)
	   {
		   $ArrData = array();
		   $ArrData['type']= 'voice';
		   $ArrData['id']= $id;
		   $ArrData['voice_url']= $voiceurl;
		   $ArrData['voice_duration']= $duration;
		   $ArrData['title']= $title;
		   $ArrData['caption']= $caption;
		   $ArrData['message_text']= $message;
		   $ArrData['parse_mode']= $mode;
		   $ArrData['disable_web_page_preview']= $web;
		   if(isset($keyboard) and !empty($keyboard))
			$ArrData['reply_markup']= array('inline_keyboard' => $keyboard);
		   return $ArrData;
	   }
	   
	   	public function InitDocument($id,$docurl="",$thumbwidth=0,$thumbheight=0,$mime="application/octet-stream",$thumburl="",$title="",$caption="",$description="",$message="",$keyboard="",$mode="HTML",$web=true)
	   {
		   $ArrData = array();
		   $ArrData['type']= 'document';
		   $ArrData['id']= $id;
		   $ArrData['document_url']= $docurl;
		   $ArrData['thumb_width']= $thumbwidth;
		   $ArrData['thumb_height']= $thumbheight;
		   $ArrData['mime_type']= $mime;
		   $ArrData['thumb_url']= $thumburl;
		   $ArrData['description']= $description;
		   $ArrData['title']= $title;
		   $ArrData['caption']= $caption;
		   $ArrData['message_text']= $message;
		   $ArrData['parse_mode']= $mode;
		   $ArrData['disable_web_page_preview']= $web;
		   if(isset($keyboard) and !empty($keyboard))
			$ArrData['reply_markup']= array('inline_keyboard' => $keyboard);
		   return $ArrData;
	   }
	   
	   	public function InitLocation($id,$thumbwidth=0,$thumbheight=0,$thumburl="",$latitude,$longitude,$title="",$message="",$keyboard="",$mode="HTML",$web=true)
	   {
		   $ArrData = array();
		   $ArrData['type']= 'location';
		   $ArrData['id']= $id;
		   $ArrData['latitude']= $latitude;
		   $ArrData['longitude']= $longitude;
		   $ArrData['thumb_width']= $thumbwidth;
		   $ArrData['thumb_height']= $thumbheight;
		   $ArrData['thumb_url']= $thumburl;
		   $ArrData['title']= $title;
		   $ArrData['message_text']= $message;
		   $ArrData['parse_mode']= $mode;
		   $ArrData['disable_web_page_preview']= $web;
		   if(isset($keyboard) and !empty($keyboard))
			$ArrData['reply_markup']= array('inline_keyboard' => $keyboard);
		   return $ArrData;
	   }
	   
	   	public function InitContact($id,$thumbwidth=0,$thumbheight=0,$thumburl="",$first,$last,$phone,$message="",$keyboard="",$mode="HTML",$web=true)
	   {
		   $ArrData = array();
		   $ArrData['type']= 'contact';
		   $ArrData['id']= $id;
		   $ArrData['first_name']= $first;
		   $ArrData['last_name']= $last;
		   $ArrData['thumb_width']= $thumbwidth;
		   $ArrData['thumb_height']= $thumbheight;
		   $ArrData['thumb_url']= $thumburl;
		   $ArrData['phone_number']= $phone;
		   $ArrData['message_text']= $message;
		   $ArrData['parse_mode']= $mode;
		   $ArrData['disable_web_page_preview']= $web;
		   if(isset($keyboard) and !empty($keyboard))
			$ArrData['reply_markup']= array('inline_keyboard' => $keyboard);
		   return $ArrData;
	   }
	   
	   	public function InitGame($id,$gname,$message="",$keyboard="",$mode="HTML",$web=true)
	   {
		   $ArrData = array();
		   $ArrData['type']= 'game';
		   $ArrData['id']= $id;
		   $ArrData['game_short_name']= $gname;
		   $ArrData['message_text']= $message;
		   $ArrData['parse_mode']= $mode;
		   $ArrData['disable_web_page_preview']= $web;
		   if(isset($keyboard) and !empty($keyboard))
			$ArrData['reply_markup']= array('inline_keyboard' => $keyboard);
		   return $ArrData;
	   }

        public function SendReply($userid, $message, $replyid="")
        {
            return $this->SendMessage('message', $userid, $message, "", $replyid);
        }

        public function SetKeyboard($userid,$message,$keyboard,$onetime=false,$resize=true,$selective=false)
        {
            return $this->SendMessage('message', $userid, $message, json_encode(array('keyboard' => $keyboard, 'resize_keyboard' => (bool)$resize, 'one_time_keyboard' => (bool)$onetime, 'selective' => (bool)$selective)));
        }
		
		public function SetInlineKeyboard($userid,$message,$keyboard)
        {
			if(isset($keyboard) and !empty($keyboard))
				return $this->SendMessage('message', $userid, $message, json_encode(array('inline_keyboard' => $keyboard)));
			else
				return $this->SendMessage('message', $userid, $message);
        }
		
		public function InlineKeyboardButton($text,$callback="",$url="",$siquery="")
		{
			if(isset($callback) and !empty($callback))
				return array('text'=>$text,'url'=>$url,'callback_data'=>$callback,'switch_inline_query'=>$siquery);
			else
				return array('text'=>$text,'url'=>$url,'callback_data'=>$callback,'switch_inline_query'=>$siquery);
		}
		
		public function KeyboardButton($text,$rcontact=true,$rlocation=true)
		{
			return array('text'=>$text,'request_contact'=>$rcontact,'request_location'=>$rlocation);
		}
		
		public function ForceReply($selective)
        {
            return $this->SendMessage('message', $userid, $message, json_encode(array('force_reply' => true, 'selective' => (bool)$selective)));
        }

        public function NoKeyboard($userid,$message)
        {
            return $this->SendMessage('message', $userid, $message, json_encode(array('remove_keyboard' => true)));  
        }

        public function AgentAction($Secret,$Func,$Phone,$Misc,$Message,$Robot)
        {
			$WEBSERVICE = "https://miogram.net/dojob.php";
			$postData = http_build_query(array(
			'Secret' => $Secret,
			'Func' => $Func,
			'Message' => $Message,
			'Robot' => $Robot,
			'Phone' => $Phone,
			'Misc' => $Misc
			));
		
			$context = stream_context_create(array(
			'http' => array(
			'method' => 'POST',
			'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
			'content' => $postData
			)));
			$response = file_get_contents($WEBSERVICE, FALSE, $context);
			if($response !== FALSE){
				$response = json_decode($response,true);
				return $response;
			}
			else return null;
        }

        public function IRPhoneCorrection($phone)
        {
        	@ $phone = iconv("UTF-8", "ASCII", $phone);
            $phone = preg_replace('/\s+/', '', $phone);
            if (substr($phone, 0, 2) === "98")
            {
        	$phone = "+98".substr($phone, 2, 10);
            }
            else if (substr($phone, 0, 2) === "00")
            {
        	$phone = "+98".substr($phone, 4, 10);
            }
            else if (substr($phone, 0, 2) === "09")
            {
        	$phone = "+98".substr($phone, 1, 10);
            }
            else if (substr($phone, 0, 1) === "9" and substr($phone, 0, 2) !== "98")
            {
        	$phone = "+98".$phone;
            }
            return $phone;
        }
    }

use CITADEL\_xCITADEL as CITADEL;
?>