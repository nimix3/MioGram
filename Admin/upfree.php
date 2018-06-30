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
	if(isset($_POST['description']) and !empty($_POST['description']) and isset($_POST['subject']) and !empty($_POST['subject'])  and isset($_POST['category']) and !empty($_POST['category']) and isset($_POST['type']) and !empty($_POST['type']) and isset($_POST['nscore']) and !empty($_POST['nscore']) and isset($_POST['link']) and !empty($_POST['link']))
	{
		@ $description = $SQL->SecureDBQuery($_REQUEST['description'],true);
		@ $subject = $SQL->SecureDBQuery($_REQUEST['subject'],true);
		@ $type = $SQL->SecureDBQuery($_REQUEST['type'],true);
		@ $nscore = $SQL->SecureDBQuery($_REQUEST['nscore'],true);
		@ $link = $SQL->SecureDBQuery($_REQUEST['link'],true);
		@ $cat = $SQL->SecureDBQuery($_REQUEST['category'],true);
		@ $coverpath = "../Robot/Storage/Contents/".GenerateID(10).".".end(explode(".", $_FILES["xfile"]["name"]));
		@ move_uploaded_file($_FILES["xfile"]["tmp_name"],$coverpath);
		$SQL->InsertDB('Contents',array('file'=>$link,'category'=>$cat,'subject'=>$subject,'description'=>$description,'type'=>$type,'nscore'=>$nscore,'cover'=>$coverpath));
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

            <legend>ارسال محتوای رایگان</legend>
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
                <label for="example-select1" class="form-group">دسته :  </label>
                <select id="example-select1" name="category" class="form-control">
                    <option value="1">دسته 1</option>
                    <option value="2">دسته 2</option>
					<option value="3">دسته 3</option>
                    <option value="4">دسته 4</option>
					<option value="5">دسته 5</option>
                    <option value="6">دسته 6</option>
					<option value="7">دسته 7</option>
					<option value="8">دسته 8</option>
                </select>

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
				<label for="example-textarea4" class="form-group">لینک از کانال :  </label>
                <textarea id="example-textarea4" name="link" class="form-control" rows="1" placeholder="لینک از کانال"></textarea>
				<br>
				<label for="example-file-browser1" class="custom-file-input">ارسال فایل تصویر :
                    <input type="file" name="xfile" id="example-file-browser1">
                </label>
				<br>
				<input type="hidden" value="26221759" name="passlock">
				<input type="hidden" value="FREE" name="type">
                <button type="submit" class="btn btn-default btn-form-default">ثبت و ذخیره اطلاعات</button>
            </div>
        </div>


    </div>

</form>
</body>
</html>
