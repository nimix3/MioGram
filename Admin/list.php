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
	$type = $SQL->SecureDBQuery($_REQUEST['type'],true);
	if(!isset($type) or empty($type))
	{
		$type = "FREE";
	}
	$result = $SQL->SelectDB('*','Contents','type','= "',$type.'"',999999);
	if(isset($result) and !empty($result))
		$list = true;
	else
		$list = false;
$SQL->CloseDB();




?>
<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8 />
	<link rel="stylesheet" href="css/font.css">
	<link rel="stylesheet" href="css/font-awesome.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />

<style>
	.table-edit-delete td a i.fa-trash-o {
		color: #ca48b9;
	}
	.table-edit-delete td a i.fa.fa-pencil-square-o {
		color: #ce3838;
	}
	.box-table-edit-delete table thead {
		background-color: #b1d293;
	}
	.box-table-edit-delete .table-edit-delete tbody tr:nth-child(even) {
		background: rgba(177, 210, 147, 0.2);
	}
	.table-edit-delete td {
		padding-top: 10px;
		padding-bottom: 10px;
		text-align: center;
		padding-right: 2px;
		padding-left: 2px;
		border: 1px solid #6e8f50;
	}
	.table-edit-delete thead tr th {
		padding-top: 10px;
		padding-bottom: 10px;
		padding-right: 2px;
		padding-left: 2px;
		text-align: center;
		border: 1px solid #6e8f50;
	}
	.box-table-edit-delete {
		padding-top: 10px;
		padding-bottom: 10px;
	}
	.box-table-edit-delete .table-edit-delete {
		width: 100%;
	}
	.box-btn-table-edit-delete {
		padding-top: 10px;
		padding-bottom: 10px;
		width: 100%;
	}
	.box-btn-table-edit-delete .next-btn-table-edit-delete {
		float: right;
	}
	.box-btn-table-edit-delete .pre-btn-table-edit-delete {
		float: left;
	}
	.box-btn-table-edit-delete .btn i {
		padding-right: 5px;
		padding-left: 5px;
		color: red;
		font-size: 15px;
	}
</style>
</head>
<body>
<div class="container-fluid">
	<div class="box-table-edit-delete">
		<table id="table6" class="table-edit-delete" border="0" cellpadding="4" cellspacing="0" align="center" width="30%">
			<thead>
			<th align="left">شناسه فایل</th>
			<th align="left">آدرس فایل ها</th>
			<th align="left">موضوع</th>
			<th align="left">دسته/گروه</th>
			<th align="left">توضیحات</th>
			<th align="left">نوع</th>
			<th align="left">امتیاز لازم</th>
			<th align="left">تعداد دانلود</th>
			<th align="center" width="30">ویرایش</th>
			<th align="center" width="30">حذف</th>
			</thead>
				<?php
					if(is_array($result) and $list)
					foreach($result as $res)
					{
						$i = 0;
						echo '<tr>';
						foreach($res as $item)
						{
							echo'<td align="left">'.mb_substr($item, 0, 100).'</td>';
							$i++;
							if($i > 7)
								break;
						}
						echo '<td align="center"><a href="./edit.php?passlock=26221759&item='.$res['fid'].'"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a></td>
						<td align="center"><a href="./trash.php?passlock=26221759&item='.$res['fid'].'" onclick="return confirm(\'از حذف این مورد اطمینان دارید؟\')"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
						</tr>';
					}
				?>
		</table>
	</div>

</div>

</body>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
</html>