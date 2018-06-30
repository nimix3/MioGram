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

if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(isset($_POST['smessage']) and !empty($_POST['smessage']))
	{
		file_put_contents("../Robot/Storage/smessage.txt",$_POST['smessage']);
	}
	
	if(isset($_POST['slink']) and !empty($_POST['slink']))
	{
		file_put_contents("../Robot/Storage/slink.txt",$_POST['slink']);
	}
	
	if(isset($_POST['sflag']) and !empty($_POST['sflag']))
	{
		if($_POST['sflag'] == "true")
		{
			$SQL->InitDB();
			$SQL->UpdateDB('Robot','1','','',array('sendflag'=>1),9999999);
			$SQL->CloseDB();
			file_put_contents("../Robot/Storage/sflag.txt","1");	
		}
	}
	
	if(isset($_POST['stimer']) and !empty($_POST['stimer']))
	{
		if($_POST['stimer'] <= 120 and $_POST['stimer'] >= 0)
		file_put_contents("../Robot/Storage/stimer.txt",time() + (intval($_POST['stimer']) * 3600));
	}
	else
	{
		file_put_contents("../Robot/Storage/stimer.txt",'0');
	}
	
	if(isset($_POST['gmessage']) and !empty($_POST['gmessage']))
	{
		file_put_contents("../Robot/Storage/gmessage.txt",$_POST['gmessage']);
	}
	
	if(isset($_POST['glink']) and !empty($_POST['glink']))
	{
		file_put_contents("../Robot/Storage/glink.txt",$_POST['glink']);
	}
	
	if(isset($_POST['gflag']) and !empty($_POST['gflag']))
	{
		if($_POST['gflag'] == "true")
		{
			$SQL->InitDB();
			$SQL->UpdateDB('Groups','1','','',array('sendflag'=>1),9999999);
			$SQL->CloseDB();
			file_put_contents("../Robot/Storage/gflag.txt","1");
		}
	}
	
	if(isset($_POST['gtimer']) and !empty($_POST['gtimer']))
	{
		if($_POST['gtimer'] <= 120 and $_POST['gtimer'] >= 0)
		file_put_contents("../Robot/Storage/gtimer.txt",time() + (intval($_POST['gtimer']) * 3600));
	}
	else
	{
		file_put_contents("../Robot/Storage/gtimer.txt",'0');
	}
}
	@ $smessage = file_get_contents("../Robot/Storage/smessage.txt");
	@ $gmessage = file_get_contents("../Robot/Storage/gmessage.txt");
	@ $sflag = file_get_contents("../Robot/Storage/sflag.txt");
	@ $gflag = file_get_contents("../Robot/Storage/gflag.txt");
	@ $slink = file_get_contents("../Robot/Storage/slink.txt");
	@ $glink = file_get_contents("../Robot/Storage/glink.txt");
	@ $stimer = file_get_contents("../Robot/Storage/stimer.txt");
	@ $gtimer = file_get_contents("../Robot/Storage/gtimer.txt");
	if($sflag == 1)
		$msg .= "سیستم درحال ارسال همگانی به شخصی اعضا می باشد تغییرات اعمال نکنید!"."<BR>";
	if($gflag == 1)
		$msg .= "سیستم درحال ارسال همگانی به گروه ها می باشد تغییرات اعمال نکنید!"."<BR>";
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="css/font.css">
    <link rel="stylesheet" href="css/font-awesome.css">
    <link rel="stylesheet" href="css/ct-paper.css">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">



</head>
<body>

<form action="" method="POST" enctype="multipart/form-data" accept-charset="utf-8" target="_self" class="form-horizontal form-plugin">

    <div class="row">
        <div class=" col-md-6 col-md-offset-3 ">

            <legend class="plugin-legend">سیستم ارسال پیام همگانی</legend>
			<h4 class="message"><?php echo $msg; ?></h4>
        </div>
        <div class=" col-md-6 col-md-offset-3 ">
            <div class="form-group">
			<label for="x" class="form-group plugin-label">متن پیام ارسال شخصی همگانی</label>
			<textarea id="x" class="form-control plugin-input" rows="5" name="smessage" placeholder="متن پیام"><?php echo $smessage; ?></textarea>
            </div>
			<BR>
			<div class="form-group">
			<label for="Xx" class="form-group plugin-label">لینک مخفی ارسال شخصی همگانی</label>
			<textarea id="Xx" class="form-control plugin-input" rows="1" name="slink" placeholder="لینک"><?php echo $slink; ?></textarea>
            </div>
			<BR>
			<div class="form-group">
			<label for="Xxx" class="form-group plugin-label">ارسال زماندار شخصی همگانی</label>
			<textarea id="Xxx" class="form-control plugin-input" rows="1" name="stimer" placeholder="چند ساعت آینده"><?php echo 0; ?></textarea>
			<p><?php echo jdate('H:i:s ,Y/n/j',$stimer); ?></p>
            </div>
			<BR><BR><BR>
			<div class="form-group">
			<label for="y" class="form-group plugin-label">متن پیام ارسال گروهی همگانی</label>
			<textarea id="y" class="form-control plugin-input" rows="5" name="gmessage" placeholder="متن پیام"><?php echo $gmessage; ?></textarea>
            </div>
			<BR>
			<div class="form-group">
			<label for="Yy" class="form-group plugin-label">لینک مخفی ارسال گروهی همگانی</label>
			<textarea id="Yy" class="form-control plugin-input" rows="1" name="glink" placeholder="لینک"><?php echo $glink; ?></textarea>
            </div>
			<BR>
			<div class="form-group">
			<label for="Yyy" class="form-group plugin-label">ارسال زماندار گروهی همگانی</label>
			<textarea id="Yyy" class="form-control plugin-input" rows="1" name="gtimer" placeholder="چند ساعت آینده"><?php echo 0; ?></textarea>
            <p><?php echo jdate('H:i:s ,Y/n/j',$gtimer); ?></p>
			</div>
        </div>
		<div class=" col-md-6 col-md-offset-3 ">
			
		</div>
        <div class=" col-md-6 col-md-offset-3 ">

            <div class="form-group">
				<input type="hidden" value="26221759" name="passlock">
				<div class="col-sm-12">
						<input type="checkbox" name="sflag" value="true" aria-label="...">
						 فعالسازی ارسال شخصی همگانی
				</div>
				<div class="col-sm-12">
						<input type="checkbox" name="gflag" value="true" aria-label="...">
						 فعالسازی ارسال گروهی همگانی
				</div>
				<BR><BR>
                <button type="submit" class="btn btn-default">ثبت اطلاعات</button>
            </div>
        </div>

    </div>

</form>
</body>
</html>