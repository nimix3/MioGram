<?php
if(isset($_REQUEST['passlock']) and !empty($_REQUEST['passlock']))
{
	if(!($_REQUEST['passlock'] == '26221759'))
		die("Security Error!");
}
else
{
	die("Security Error!");
}
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
	$SQL = new SQLParser($DNA->GetSQLConnInfo());
	$Logger = new LogProc($DNA->GetLogOptions());
	$Sess = new Session();
	
	$AdminCfg = $DNA->GetAdminOptions();
	$Data = $Sess->SessionGetData();
	if($Data['login'] == md5($AdminCfg['Username'] + date('YMD')) and $Data['session'] == sha1($AdminCfg['Password'] + date('YMD')))
	{
		// Do Nothing //
	}
	else
	{
		Header("Location: /login.php");
		exit();
	}
$SQL->InitDB();
  @ $tgid = $SQL->SecureDBQuery($_REQUEST['tgid'],true);
  @ $operator = 'ADMIN';
  @ $message = $SQL->SecureDBQuery($_REQUEST['message'],true);
  
    $pretxt = "📤 پاسخ راهنمای سامانه: \n\n";
    $filex = "Admin";
    if($operator == "ADMIN")
    {
      $filex = "Admin";
      $pretxt = "📤 پاسخ راهنمای سامانه: \n\n";
	  $optitle = "پشتیبانی";
    }
    else
      exit();

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($tgid) and !empty($tgid) and isset($operator) and !empty($operator) and isset($message) and !empty($message))
{
if(isset($_REQUEST['refer']) and !empty($_REQUEST['refer']))
{
	if($_REQUEST['refer'] == "PAYCHECK")
		$Rec = "PAYCHECK";
	else if($_REQUEST['refer'] == "SUPPORT")
		$Rec = "SUPPORT";
	else
		$Rec = "ADMIN";
	
	$SQL->UpdateDB('Chats','xfrom','=',$tgid.' AND `seen` = 0',array('xto'=>$Rec),99999);
}
else
{
    if($_REQUEST['message'] == "." or $_REQUEST['message'] == " ")
    {
      $SQL->UpdateDB('Chats','xfrom','=',$tgid,array('seen'=>'1'),99999);
		$resu = $SQL->SelectDB('*','Chats','xto','= "',$operator.'" AND `seen` = 0 ORDER BY `time` DESC');
		$nextuser = 0;
		if(isset($resu[0]) and !empty($resu[0]))
		{
			$nextuser = $resu[0]['xfrom'];
		}
	  Header("Location: ".'./chat.php?passlock=26221759&tgid='.$nextuser.'&operator='.$operator);
	  exit();
    }
    else
    {
	  if(isset($_FILES['UploadedFile']) and !empty($_FILES['UploadedFile']))
	  {
		if(strpos($_FILES["UploadedFile"]["name"],".mp3"))
		{
			$type = "audio";
		}else if(strpos($_FILES["UploadedFile"]["name"],".mp4"))
		{
			$type = "video";
		}else
		{
			$type = "document";
		}
		@ move_uploaded_file($_FILES["UploadedFile"]["tmp_name"], "../Robot/Storage/Temp/".$_FILES["UploadedFile"]["name"]);
		$res = json_decode($TgBot->SendFile($type,$tgid,"../Robot/Storage/Temp/".$_FILES["UploadedFile"]["name"]," فایل ضمیمه از ".$optitle),true);
		@ unlink("../Robot/Storage/Temp/".$_FILES["UploadedFile"]["name"]);
		
		$ttype = $res['result']['document']['file_id'];
		if(isset($res['result']['video']['file_id']) and !empty($res['result']['video']['file_id']))
			$ttype = $res['result']['video']['file_id'];
		if(isset($res['result']['audio']['file_id']) and !empty($res['result']['audio']['file_id']))
			$ttype = $res['result']['audio']['file_id'];
		if(isset($res['result']['document']['file_id']) and !empty($res['result']['document']['file_id']))
			$ttype = $res['result']['document']['file_id'];
		if(isset($ttype) and !empty($ttype))
		{
			$SQL->InsertDB('Chats',array('chatid'=>GenerateID(8),'xfrom'=>$operator,'xto'=>$tgid,'time'=>time(),'message'=>"FILE::".$ttype,'seen'=>'1','tags'=>''));
			@ file_put_contents("../Robot/Storage/Temp/".$filex.".html",time()."::".$tgid."::"."FILE->".$ttype.PHP_EOL,FILE_APPEND);
		}
	  }
	$SQL->InsertDB('Chats',array('chatid'=>GenerateID(8),'xfrom'=>$operator,'xto'=>$tgid,'time'=>time(),'message'=>str_replace("%NAME%",GetClientName($tgid),$message),'seen'=>'1','tags'=>GetHashtag($message)));
	$SQL->UpdateDB('Chats','xfrom','=',$tgid,array('seen'=>'1'),99999);
    //$TgBot->SetInlineKeyboard($tgid,$pretxt.str_replace("%LINK%","https://telegram.me/medarbot?start=".$tgid,str_replace("%NAME%",GetClientName($tgid),$_REQUEST['message'])),array(array($TgBot->InlineKeyboardButton("پاسخ","REPLY_".$operator),$TgBot->InlineKeyboardButton("اتمام چت","REPLY_END_".$operator))));
	$TgBot->SendMessage('message', $tgid, $pretxt.TransText($_REQUEST['message'],$tgid));
	@ file_put_contents("../Robot/Storage/Temp/".$filex.".html",time()."::".$tgid."::".$message.PHP_EOL,FILE_APPEND);
    }
}
}
//////////////////////////////
$resx = $SQL->SelectDB('*','Robot','tgid','=',$tgid);
		
		if(!empty($resx[0]['gender']) and $resx[0]['gender'] == "male")
			$gender = "مرد";
		else if(!empty($resx[0]['gender']) and $resx[0]['gender'] == "female")
			$gender = "زن";
		else
			$gender = "مشخص نشده";
		
		$msgg = "🆔 شناسه کاربری : ".$resx[0]['uid'].PHP_EOL."🆔 شناسه تلگرام : ".$tgid.PHP_EOL."💰امتیاز : ".intval($resx[0]['score']).PHP_EOL."📲تلفن همراه : ".intval($resx[0]['phone']).PHP_EOL.PHP_EOL."👫 جنسیت : ".$gender.PHP_EOL."🎉تاریخ عضویت : ".jdate('H:i:s ,Y/n/j',$resx[0]['stime']).PHP_EOL."⏰زمان آخرین استفاده : ".jdate('H:i:s ,Y/n/j',$resx[0]['lastuse']).PHP_EOL."👥 تعداد دعوت ها : ".intval(count(json_decode($resx[0]['subset'],true)));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>سیستم مدیریت پیام های سامانه مدار زندگی</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <link href="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
    	body{
   font: 18px/20px 'b nazanin';
	directioin: rtl !important;
	/* text-align:right; */
    margin-top:20px;
    background:#ebeef0;
}
.panel {
    box-shadow: 0 2px 0 rgba(0,0,0,0.075);
    border-radius: 0;
    border: 0;
    margin-bottom: 24px;
	background-color: #8a8a8a;
}
.panel .panel-heading, .panel>:first-child {
    border-top-left-radius: 0;
    border-top-right-radius: 0;
}
.panel-heading {
    position: relative;
    height: 50px;
    padding: 0;
    border-bottom:1px solid #eee;
}
.panel-control {
    height: 100%;
    position: relative;
    float: right;
    padding: 0 15px;
}
.panel-title {
    font-weight: normal;
    padding: 0 20px 0 20px;
    font-size: 1.416em;
    line-height: 50px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.panel-control>.btn:last-child, .panel-control>.btn-group:last-child>.btn:first-child {
    border-bottom-right-radius: 0;
}
.panel-control .btn, .panel-control .dropdown-toggle.btn {
    border: 0;
}
.nano {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}
.nano>.nano-content {
    position: absolute;
    overflow: scroll;
    overflow-x: hidden;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
}
.pad-all {
    padding: 15px;
}
.mar-btm {
    margin-bottom: 15px;
}
.media-block .media-left {
    display: block;
    float: left;
}
.img-sm {
    width: 46px;
    height: 46px;
}
.media-block .media-body {
    display: block;
    overflow: hidden;
    width: auto;
}
.pad-hor {
    padding-left: 15px;
    padding-right: 15px;
}
.speech {
    position: relative;
    background: #b7dcfe;
    color: #317787;
    display: inline-block;
    border-radius: 0;
    padding: 12px 20px;
}
.speech:before {
    content: "";
    display: block;
    position: absolute;
    width: 0;
    height: 0;
    left: 0;
    top: 0;
    border-top: 7px solid transparent;
    border-bottom: 7px solid transparent;
    border-right: 7px solid #b7dcfe;
    margin: 15px 0 0 -6px;
}
.speech-right>.speech:before {
    left: auto;
    right: 0;
    border-top: 7px solid transparent;
    border-bottom: 7px solid transparent;
    border-left: 7px solid #ffdc91;
    border-right: 0;
    margin: 15px -6px 0 0;
}
.speech .media-heading {
    font-size: 1.2em;
    color: #317787;
    display: block;
    border-bottom: 1px solid rgba(0,0,0,0.1);
    margin-bottom: 10px;
    padding-bottom: 5px;
    font-weight: 300;
}
.speech-time {
    margin-top: 20px;
    margin-bottom: 0;
    font-size: .8em;
    font-weight: 300;
}
.media-block .media-right {
    float: right;
}
.speech-right {
    text-align: right;
}
.pad-hor {
    padding-left: 15px;
    padding-right: 15px;
}
.speech-right>.speech {
    background: #ffda87;
    color: #a07617;
    text-align: right;
}
.speech-right>.speech .media-heading {
    color: #a07617;
}
.btn-primary, .btn-primary:focus, .btn-hover-primary:hover, .btn-hover-primary:active, .btn-hover-primary.active, .btn.btn-active-primary:active, .btn.btn-active-primary.active, .dropdown.open>.btn.btn-active-primary, .btn-group.open .dropdown-toggle.btn.btn-active-primary {
    background-color: #579ddb;
    border-color: #5fa2dd;
    color: #fff !important;
}
.btn {
    cursor: pointer;
    /* background-color: transparent; */
    color: inherit;
    padding: 6px 12px;
    border-radius: 0;
    border: 1px solid 0;
    font-size: 14px;
    line-height: 1.42857;
    vertical-align: middle;
    -webkit-transition: all .25s;
    transition: all .25s;
}
.form-control {
    font-size: 18px;
    height: 100%;
    border-radius: 0;
    box-shadow: none;
    border: 1px solid #e9e9e9;
    transition-duration: .5s;
}
.nano>.nano-pane {
    background-color: rgba(0,0,0,0.1);
    position: absolute;
    width: 5px;
    right: 0;
    top: 0;
    bottom: 0;
    opacity: 0;
    -webkit-transition: all .7s;
    transition: all .7s;
}
.inputfile {
	width: 0.1px;
	height: 0.1px;
	opacity: 0;
	overflow: hidden;
	position: absolute;
	z-index: -1;
}
.inputfile + label {
  color: #fff;
  background-color: #337ab7;
  border-color: #2e6da4;
  font-size: 14px;
  width:100%;
  height:30px;
  text-align:center;
}
.inputfile:focus + label,
.inputfile + label:hover {
    background-color: red;
}
    </style>
</head>
<body>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div  style="width:100%;!important">
    <div class=" col-lg-5">
        <div class="panel" id="panelid">
        	<!--Heading-->
    
    		<!--Widget body-->
    		<div id="demo-chat-body" class="collapse in">
    			<div class="nano has-scrollbar" style="height:380px">
    				<div class="nano-content pad-all" tabindex="0" style="right: -17px;">
    					<ul class="list-unstyled media-block">
							<?php
							$resf = $SQL->SelectDB('*','Chats','xfrom','=',$tgid.' OR `xto` = '.$tgid.' ORDER BY `time`',999999);
							$resu = $SQL->SelectDB('*','Chats','xto','= "',$operator.'" AND `seen` = 0 ORDER BY `time` DESC');
							$nextuser = 0;
							if(isset($resu[0]) and !empty($resu[0]))
							{
								$nextuser = $resu[0]['xfrom'];
							}
							if(isset($resf) and !empty($resf))
							foreach($resf as $data)
							{
								$mssg = $data["message"];
								if(strpos($data["message"],"FILE::") !== false)
								{
									$mssg = '<a href="./download.php?fid='.str_replace("FILE::","",$data["message"]).' ">Download File</a>';
								}
								else
								{
									$mssg = nl2br($data["message"]);
								}
								if($data['xfrom'] == $tgid)
								{
									echo '
									<li class="mar-btm">
										<div class="media-left">
											<img src="images/avatar2.png" class="img-circle img-sm" alt="Profile Picture">
										</div>
										<div class="media-body pad-hor">
											<div class="speech">
												<a href="#" class="media-heading">'.GetClientName($data["xfrom"]).'</a>
												<p>'.$mssg.'</p>
												<p class="speech-time">
													<i class="fa fa-clock-o fa-fw"></i> '.jdate('H:i:s ,Y/n/j',$data["time"]).'
												</p>
											</div>
										</div>
									</li> ';
								}
								else
								{
									echo '
									<li class="mar-btm">
										<div class="media-right">
											<img src="images/avatar1.png" class="img-circle img-sm" alt="Profile Picture">
										</div>
										<div class="media-body pad-hor speech-right">
											<div class="speech">
												<a href="#" class="media-heading">'.$optitle.'</a>
												<p>'.$mssg.'</p>
												<p class="speech-time">
													<i class="fa fa-clock-o fa-fw"></i> '.jdate('H:i:s ,Y/n/j',$data["time"]).'
												</p>
											</div>
										</div>
									</li> ';
								}
							}
							$SQL->CloseDB();
							?>
    					</ul>
    				</div>
    			<div class="nano-pane"><div class="nano-slider" style="height: 141px; transform: translate(0px, 0px);"></div></div></div>
    
    			<!--Widget footer-->
    			<div class="panel-footer">
    				<div class="row">
					<form action="" method="post" enctype="multipart/form-data" accept-charset="UTF-8">
    					<input type="hidden" name="tgid" value="<?php echo $tgid; ?>">
						<input type="hidden" name="passlock" value="26221759">
						<input type="hidden" name="operator" value="<?php echo $operator; ?>">
						<div class="col-xs-9">
    						<textarea name="message" id="msgpool"  dir="RTL" placeholder="متن مورد نظر خود را بنویسید" class="form-control chat-input" text-align="right"></textarea>
    					</div>
    					<div class="col-xs-3">
    						<button class="btn btn-primary btn-block" type="submit">ارسال</button>
							<input class="inputfile" id="file" type="file" name="UploadedFile" />
							<label for="file">آپلود فایل</label>

    					</div>
					</form>
					</br>
    			<table style="text-align: center;  margin-left: auto; margin-right: auto;width:100%; float: left">
		<tr>
			<td><a href="./dialog.php?passlock=26221759&operator=<?php echo $operator; ?>&page=<?php echo 1; ?>" class="btn btn-primary" title="«بازگشت">« بازگشت</a></td>
			<td>
					<form action="" method="post" enctype="application/x-www-form-urlencoded" accept-charset="UTF-8">
    					<input type="hidden" name="message" value=".">
						<input type="hidden" name="tgid" value="<?php echo $tgid; ?>">
						<input type="hidden" name="operator" value="<?php echo $operator; ?>">
						<input type="hidden" name="passlock" value="26221759">
						<button class="btn btn-primary btn-block" type="submit">بستن</button>
					</form></td>
					<?php
						if($nextuser)
						echo '<td><a href="./chat.php?passlock=26221759&tgid='.$nextuser.'&operator='.$operator.'" class="btn btn-primary" title="پیام بعدی »">پیام بعدی »</a></td>';
					?>
		</tr>
		</table>
    				</div>
				</div>
    		</div>
    	</div>
		</BR>
		<table style="text-align: center;  margin-left: auto; margin-right: auto;width:100%; float: left">
		<tr>
			
		</tr>
		</table>
		</BR>
	</div>
	
		    <div class=" col-lg-3">
				<table class="table table-bordered " style="background-color:white;text-align: center;  margin-right: auto;
				margin-left: auto;">
					<tr><td><strong>اطلاعات کاربر</strong></td></tr>
					<tr><td  style="font-size:12px;"><?php echo nl2br($msgg); ?></td></tr>
				</table>
			</div>	
			
			
	    <div class=" col-lg-4">
	<table class="table table-bordered " style="background-color:white;text-align: center;  margin-right: auto;  margin-left: auto;">
	<?php 
	$recent = explode(PHP_EOL,file_get_contents("../Robot/Storage/recent.db"));
	if(is_array($recent))
	{
		foreach($recent as $item)
		{
			echo '<tr><td><a href="#" onclick="Insert(this); return false;">'.$item.'</a></td></tr>'.PHP_EOL;
		}
	}
	else
	{
		echo '<tr><td><a href="#" onclick="Insert(this); return false;">'.file_get_contents("../Robot/Storage/recent.db").'</a></td></tr>'.PHP_EOL;
	}
	?>
	</table>
</div>
</div>
<script src="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script type="text/javascript">
	function Insert(th){
		document.getElementById("msgpool").value += th.text; //.innerHTML
	}
	element = document.getElementById("panelid")
element.scrollIntoView(true);
</script>
</body>
</html>