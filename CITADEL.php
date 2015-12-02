<?php
// CITADEL TBK (Telegram Bot SDK) V.1 By NIMIX3 for MioGram Platform.
// NOTE : YOU CANNOT EDIT or SELL This CODE FOR COMMERCIAL PURPOSE!
// Under GNU GPL V.2 License
namespace CITADEL;
    class _xCITADEL
    {
        protected $API;
        protected $DATA;
        
        public function __construct($API){
        $this->API = $API;
        $this->DATA = json_decode(file_get_contents('php://input'), true);
        }

        public function sendFile($type, $user, $content)
        {
          $api_key = $this->API;
         $apiendpoint = ucfirst($type);
          if ($type == 'photo' || $type == "audio" || $type == "video" || $type == "document") {
            $mimetype = mime_content_type($content);
	    	$ext = pathinfo($content, PATHINFO_EXTENSION);
	    	$content = '@'.$content;
            } elseif ($type == "message") {
               $type = 'text';
           }
        $ch = curl_init("https://api.telegram.org/bot".$api_key."/send".$apiendpoint);
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
			'parse_mode' => 'Markdown',
			//'reply_markup' => $keyboard,
            $type => $content
        ),
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 6000,
        CURLOPT_SSL_VERIFYPEER => false
       ));
       curl_exec($ch);
       curl_close($ch);
        }

        public function GetFileFromUrl($url,$dir="Data/",$hash="")
        {
           $filename = str_replace("/","",parse_url($url,PHP_URL_PATH));
        @ file_put_contents($dir.$hash.$filename,file_get_contents($url));
        if(file_exists($dir.$hash.$filename))
        {
	    return $dir.$hash.$filename;
        }
        return NULL;
        }

        public function getRawData()
        {
        return $this->DATA;
        }

        public function SendRawData($type,$Options)
        {
          $api_key = $this->API;
          $apiendpoint = ucfirst($type);
         $ch = curl_init("https://api.telegram.org/bot".$api_key."/send".$apiendpoint);
        curl_setopt_array($ch, array(
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HEADER => false,
        CURLOPT_HTTPHEADER => array(
            'Host: api.telegram.org',
            'Content-Type: multipart/form-data'
        ),
        CURLOPT_POSTFIELDS => $Options,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 6000,
        CURLOPT_SSL_VERIFYPEER => false
        ));
       curl_exec($ch);
       curl_close($ch);
       }

        public function getChatName(){
        return $this->DATA["message"]["chat"]["title"];
        }

        public function getChatID(){
        return $this->DATA["message"]["chat"]["id"];
        }

        public function getChatType(){
        return $this->DATA["message"]["chat"]["type"];
        }

        public function getChatUsername(){
        return $this->DATA["message"]["chat"]["username"];
        }

        public function getForwardedUserID(){
        return $this->DATA["message"]["forward_from"]["id"];
        }

        public function getForwardedFirstName(){
        return $this->DATA["message"]["forward_from"]["first_name"];
        }

        public function getForwardedLastName(){
        return $this->DATA["message"]["forward_from"]["last_name"];
        }

        public function getForwardedUsername(){
        return $this->DATA["message"]["forward_from"]["username"];
        }

        public function getContactPhone(){
        return $this->DATA["message"]["contact"]["phone_number"];
        }

        public function getContactUserID(){
        return $this->DATA["message"]["contact"]["user_id"];
        }

        public function getContactFirstName(){
        return $this->DATA["message"]["contact"]["first_name"];
        }

        public function getContactLastName(){
        return $this->DATA["message"]["contact"]["last_name"];
        }

        public function getLocationLongitude(){
        return $this->DATA["message"]["location"]["longitude"];
        }

        public function getLocationLatitude(){
        return $this->DATA["message"]["location"]["latitude"];
        }

        public function getNewChatAddedID(){
        return $this->DATA["message"]["new_chat_participant"]["id"];
        }

        public function getNewChatAddedFirstName(){
        return $this->DATA["message"]["new_chat_participant"]["first_name"];
        }

        public function getNewChatAddedLastName(){
        return $this->DATA["message"]["new_chat_participant"]["last_name"];
        }

        public function getNewChatAddedUsername(){
        return $this->DATA["message"]["new_chat_participant"]["username"];
        }

        public function getNewChatRemovedID(){
        return $this->DATA["message"]["left_chat_participant"]["id"];
        }

        public function getNewChatRemovedFirstName(){
        return $this->DATA["message"]["left_chat_participant"]["first_name"];
        }

        public function getNewChatRemovedLastName(){
        return $this->DATA["message"]["left_chat_participant"]["last_name"];
        }

        public function getNewChatRemovedUsername(){
        return $this->DATA["message"]["left_chat_participant"]["username"];
        }

        public function getCommand()
        {
        return explode(" ", $this->DATA["message"]["text"])[0];
        }

        public function getCommandData(){
        return str_replace((explode($this->DATA["message"]["text"])[0])." ",'',$this->DATA["message"]["text"]);
        }

        public function getMessage()
        {
        return $this->DATA["message"]["text"];
        }

        public function getUserID()
        {
        return $this->DATA["message"]["from"]["id"];
        }

        public function getUsername()
        {
        return $this->DATA["message"]["from"]["username"];
        }

        public function getFirstName()
        {
        return $this->DATA["message"]["from"]["first_name"];
        }

        public function getLastName()
        {
        return $this->DATA["message"]["from"]["last_name"];
        }

        public function SendMessage($type, $user, $content, $keyboard="")
        {
          $api_key = $this->API;
          $apiendpoint = ucfirst($type);
          if ($type == 'photo' || $type == "audio" || $type == "video" || $type == "document") {
          $mimetype = mime_content_type($content);
          $content = new CurlFile($content, $mimetype);
           } elseif ($type == "message") {
           $type = 'text';
          }
         $ch = curl_init("https://api.telegram.org/bot".$api_key."/send".$apiendpoint);
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
			'reply_markup' => $keyboard,
            $type => $content
        ),
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 6000,
        CURLOPT_SSL_VERIFYPEER => false
        ));
       curl_exec($ch);
       curl_close($ch);
       }

        public function MioStackMsg($type, $user, $content, $keyboard="" , $Secret="")
        {
          $apiendpoint = ucfirst($type);
          if ($type == 'photo' || $type == "audio" || $type == "video" || $type == "document") {
          $mimetype = mime_content_type($content);
          $content = new CurlFile($content, $mimetype);
           } elseif ($type == "message") {
           $type = 'text';
          }
         $ch = curl_init("https://miogram.net/Terminal.php");
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
			'reply_markup' => $keyboard,
            'Secret' => $Secret,
            'EndPoint' => $apiendpoint,
            $type => $content
        ),
        CURLOPT_TIMEOUT => 0,
        CURLOPT_CONNECTTIMEOUT => 6000,
        CURLOPT_SSL_VERIFYPEER => false
        ));
       curl_exec($ch);
       curl_close($ch);
       }

        public function SetKeyboard($userid,$message,$keyboard,$onetime=false,$resize=true)
        {
          self::sendmessage('message', $userid, $message, json_encode(array('keyboard' => $keyboard, 'resize_keyboard' => (bool)$resize, 'one_time_keyboard' => (bool)$onetime)));
        }

        public function NoKeyboard($userid,$message)
        {
          self::SendMessage('message', $userid, $message, json_encode(array('hide_keyboard' => true)));  
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
			else return false;
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
