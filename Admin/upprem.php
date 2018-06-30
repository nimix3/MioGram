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
	
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
$SQL->InitDB();
	if(isset($_POST['description']) and !empty($_POST['description']) and isset($_POST['subject']) and !empty($_POST['subject']) and isset($_POST['type']) and !empty($_POST['type']) and isset($_POST['nscore']) and !empty($_POST['nscore']) and isset($_POST['xlink']) and !empty($_POST['xlink'])  and isset($_POST['ylink']) and !empty($_POST['ylink']) and isset($_FILES['efile']) and !empty($_FILES['efile']))
	{
		@ $description = $SQL->SecureDBQuery($_REQUEST['description'],true);
		@ $subject = $SQL->SecureDBQuery($_REQUEST['subject'],true);
		@ $type = $SQL->SecureDBQuery($_REQUEST['type'],true);
		@ $nscore = $SQL->SecureDBQuery($_REQUEST['nscore'],true);
		$xlink = $SQL->SecureDBQuery($_REQUEST['xlink'],true);
		$ylink = $SQL->SecureDBQuery($_REQUEST['ylink'],true);
		$zlink = $SQL->SecureDBQuery($_REQUEST['zlink'],true);
		@ $coverpath = "../Robot/Storage/Contents/".GenerateID(10).".".end(explode(".", $_FILES["efile"]["name"]));
		@ move_uploaded_file($_FILES["efile"]["tmp_name"],$coverpath);
		$SQL->InsertDB('Contents',array('file'=>trim($xlink."::".$ylink."::".$zlink,"::"),'category'=>'0','subject'=>$subject,'description'=>$description,'type'=>$type,'nscore'=>$nscore,'cover'=>$coverpath));
		$msg = "اطلاعات با موفقیت ثبت شد";
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

            <legend>ارسال محتوای ویژه</legend>
			<h4 class="message"><?php echo $msg; ?></h4>
        </div>
        <div class=" col-sm-6">

            <div class="form-group">
                <label for="example-textarea1" class="form-group">موضوع :</label>
                <textarea id="example-textarea1" name="subject" class="form-control" rows="1" placeholder="تایپ موضوع"></textarea>


            </div>
        </div>
        <div class=" col-sm-6">

            <div class="form-group">
                <label for="example-textarea2" class="form-group">توضیحات : </label>
                <textarea id="example-textarea2" name="description" class="form-control" rows="1" placeholder="تایپ توضیحات"></textarea>

            </div>
        </div>
        <div class=" col-sm-6">

            <div class="form-group">
                <label for="example-textarea3" class="form-group">امتیاز :  </label>
                <textarea id="example-textarea3" name="nscore" class="form-control" rows="1" placeholder="تایپ امتیاز"></textarea>

            </div>
        </div>

        <div class=" col-xs-12">

            <div class="form-group">
				<label for="example-file-browser4" class="custom-file-input">ارسال فایل تصویر :
                    <input type="file" name="efile" id="example-file-browser4">
                </label>
				
				<label for="example-textarea5" class="form-group">فایل ویدئویی :
                <textarea id="example-textarea5" name="xlink" class="form-control" rows="1" placeholder="فایل ویدئویی"></textarea>
				</label>
				
				<label for="example-textarea6" class="form-group">فایل صوتی :
                <textarea id="example-textarea6" name="ylink" class="form-control" rows="1" placeholder="فایل صوتی"></textarea>
				</label>
				
				<label for="example-textarea7" class="form-group">فایل متنی :
                <textarea id="example-textarea7" name="zlink" class="form-control" rows="1" placeholder="فایل متنی"></textarea>
				</label>
				
				<input type="hidden" value="26221759" name="passlock">
				<input type="hidden" value="PREMIUM" name="type">
                <button type="submit" class="btn btn-default btn-form-default">ثبت و ذخیره اطلاعات</button>
            </div>
        </div>


    </div>

</form>
</body>
</html>
