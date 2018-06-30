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
	$item = intval($SQL->SecureDBQuery($_REQUEST['item'],true));
	$result = $SQL->SelectDB('*','Contents','fid','=',$item);
	if(isset($result) and !empty($result))
		$edit = true;
	else
		$edit = false;
$SQL->CloseDB();
	
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
$SQL->InitDB();
	if(isset($_POST['fid']) and !empty($_POST['fid']) and isset($_POST['file']) and !empty($_POST['file']) and isset($_POST['description']) and !empty($_POST['description']) and isset($_POST['subject']) and !empty($_POST['subject'])  and isset($_POST['category']) and !empty($_POST['category']) and isset($_POST['type']) and !empty($_POST['type']) and isset($_POST['nscore']) and !empty($_POST['nscore']))
	{
		@ $subject = $SQL->SecureDBQuery($_REQUEST['subject'],true);
		@ $cat = $SQL->SecureDBQuery($_REQUEST['category'],true);
		@ $description = $SQL->SecureDBQuery($_REQUEST['description'],true);
		@ $type = $SQL->SecureDBQuery($_REQUEST['type'],true);
		@ $nscore = $SQL->SecureDBQuery($_REQUEST['nscore'],true);
		@ $fid = $SQL->SecureDBQuery($_REQUEST['fid'],true);
		@ $file = $SQL->SecureDBQuery($_REQUEST['file'],true);
		@ $SQL->UpdateDB('Contents','fid','=',$fid,array('subject'=>$subject,'description'=>$description,'type'=>$type,'category'=>$cat, 'nscore'=>$nscore, 'file'=>$file));
		$msg = "اطلاعات با موفقیت ویرایش شد";
	}
	else
	{
		$msg = "اطلاعات وارد شده کامل نیست!!!";
	}
$SQL->CloseDB();
}
  
  
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
<form action="" method="POST" enctype="multipart/form-data" accept-charset="utf-8" target="_self" class="form-horizontal">

    <div class="row">
        <div class=" col-sm-6 col-sm-offset-6 ">

            <legend>ارسال محتوای رایگان</legend>
			<h4 class="message"><?php echo $msg; ?></h4>
        </div>
		<?php
		if($edit and is_array($result))
        echo '<div class=" col-sm-6">
				<div class="form-group">
					<label for="example-textarea1" class="form-group">موضوع :</label>
					<textarea id="example-textarea1" name="subject" class="form-control" rows="1" placeholder="مثلا: روز خوب">'.$result[0]['subject'].'</textarea>
				</div>
			</div>';
		echo '<div class=" col-sm-6">
				<div class="form-group">
					<label for="example-textarea2" class="form-group">دسته :</label>
					<textarea id="example-textarea2" name="category" class="form-control" rows="1" placeholder="مثلا: 5">'.$result[0]['category'].'</textarea>
				</div>
			</div>';
		echo '<div class=" col-sm-6">
				<div class="form-group">
					<label for="example-textarea3" class="form-group">توضیحات :</label>
					<textarea id="example-textarea3" name="description" class="form-control" rows="1" placeholder="مثلا: هوا بسیار خوب است">'.$result[0]['description'].'</textarea>
				</div>
			</div>';
		echo '<div class=" col-sm-6">
				<div class="form-group">
					<label for="example-textarea4" class="form-group">نوع :</label>
					<textarea id="example-textarea4" name="type" class="form-control" rows="1" placeholder="مثلا: FREE">'.$result[0]['type'].'</textarea>
				</div>
			</div>';
		echo '<div class=" col-sm-6">
				<div class="form-group">
					<label for="example-textarea5" class="form-group">امتیاز لازم :</label>
					<textarea id="example-textarea5" name="nscore" class="form-control" rows="1" placeholder="مثلا: 5">'.$result[0]['nscore'].'</textarea>
				</div>
			</div>';
		echo '<div class=" col-sm-6">
			<div class="form-group">
				<label for="example-textarea6" class="form-group">فایل ها:</label>
				<textarea id="example-textarea6" name="file" class="form-control" rows="1" placeholder="مثلا: https://t.me">'.$result[0]['file'].'</textarea>
			</div>
		</div>';
		?>
		
        <div class=" col-xs-12">
            <div class="form-group">
				<input type="hidden" value="26221759" name="passlock">
				<input type="fid" value="<?php echo $item; ?>" name="fid">
                <button type="submit" class="btn btn-default btn-form-default">ویرایش و ذخیره اطلاعات</button>
            </div>
        </div>


    </div>

</form>
</body>
</html>
