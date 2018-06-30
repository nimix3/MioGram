<?php
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
require_once('../Robot/Library/core/SESSION.php');
require_once('../Robot/Library/core/INITILIZE.php');
$items = parse_ini_file("../Robot/Plugins/Hooks/plugins.db");
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
$result = $SQL->SelectDB('COUNT(*) as total','Robot','active','=',1);
$total = $result[0]['total'];
$SQL->CloseDB();
$botname = $DNA->GetBotUsername();
$website = $DNA->GetWebsiteUrl();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>پنل مدیریت</title>
	
	<link rel="stylesheet" href="css/font.css">
	<link rel="stylesheet" href="css/font-awesome.css">
	<link rel="stylesheet" href="css/ct-paper.css">
	<link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
	
	
	
</head>
<body>	
<div class="main-wrapper" >
	<header>	
		<section class="container-fluid">
			<div class="row">
				
				<div class="col-sm-2 col-sm-push-10 col-xs-2 col-xs-push-10">
					<ul class="logo-menu">
						<li><a href="<?php echo $website; ?>"><img src="images/logo.png"/></a></li>
					</ul>
				</div>
				<div class="col-sm-10 col-sm-pull-2 col-xs-10 col-xs-pull-2 ">
					<ul class="login-menu">
						
						<li><a href="#" title="خروج"><i class="fa fa-sign-out" aria-hidden="true"></i><span>خروج</span></a></li>
						<li><a href="#" title="تغییر کلمه عبور"><i class="fa fa-user-circle-o" aria-hidden="true"></i><span>تعداد اعضا : <?php echo $total; ?></span></a></li>
						<li><a ><i class="fa fa-unlock-alt" aria-hidden="true"></i><?php echo $botname; ?></a></li>
					</ul>
				</div>
				
			</div>
		</section>
	</header>
	<section class="sec-main-content">
		<div class="container">
		<div class="row">
			<div class="col-md-2 col-md-push-10 col-sm-3 col-sm-push-9 panel-right-main-box">
				
				
				<div class="panel panel-default">
						<div class="panel-right-title-box">
						<div class="panel-heading" role="tab" id="headingTwo">
							<h4 class="panel-title">
								<a class="collapsed " role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
									پنل مدیریت
								</a>
							</h4>
						</div>
					</div>
					<div class="panel-right-content-box">
						<div id="collapseTwo" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
							<div class="panel-body panel-body-main">
								<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  
  
  <div class="panel panel-default content-panel-body">
    <div class="panel-heading" role="tab" id="heading1">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
          افزونه ها
        </a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
      <div class="panel-body">
		<ul id="dropdown-user-menu">
			<?php
				if(is_array($items) and !empty($items))
				foreach($items as $pname => $prio)
				{
					echo '<li><a href="./options.php?plugin='.str_replace(".php","",$pname).'" target="xFrame"><i class="fa fa-hand-o-left" aria-hidden="true"></i>'.str_replace(".php","",$pname).'</a></li>';
				}
			?>
		</ul>
      </div>
    </div>
  </div>
  <div class="panel panel-default content-panel-body">
    <div class="panel-heading" role="tab" id="heading">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="false" aria-controls="collapse2">
           تنظیمات کلی
        </a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse" role="tabpane2" aria-labelledby="heading2">
      <div class="panel-body">
		<ul id="dropdown-user-menu">
			<li><a href="./kbd.php?passlock=26221759" target="xFrame"><i class="fa fa-keyboard-o" aria-hidden="true" target="xFrame"></i>کیبورد اصلی ربات</a></li>
			<li><a href="./upfree.php?passlock=26221759" target="xFrame"><i class="fa fa-cog" aria-hidden="true" target="xFrame"></i>آپلود مطالب رایگان</a></li>
			<li><a href="./list.php?type=FREE&passlock=26221759" target="xFrame"><i class="fa fa-cog" aria-hidden="true" target="xFrame"></i>ویرایش مطالب رایگان</a></li>
			<li><a href="./upprem.php?passlock=26221759" target="xFrame"><i class="fa fa-cog" aria-hidden="true" target="xFrame"></i>آپلود مطالب ویژه</a></li>
			<li><a href="./list.php?type=PREMIUM&passlock=26221759" target="xFrame"><i class="fa fa-cog" aria-hidden="true" target="xFrame"></i>ویرایش مطالب ویژه</a></li>
			<li><a href="./dialog.php?passlock=26221759" target="xFrame"><i class="fa fa-cog" aria-hidden="true"></i>گفتگوها</a></li>
			<li><a href="./sender.php?passlock=26221759" target="xFrame"><i class="fa fa-cog" aria-hidden="true"></i>ارسال همگانی</a></li>
			<li><a href="./survey.php?passlock=26221759" target="xFrame"><i class="fa fa-cog" aria-hidden="true"></i>نظرات کاربران</a></li>
		</ul>
      </div>
    </div>
  </div>
  <div class="panel panel-default content-panel-body">
    <div class="panel-heading" role="tab" id="heading">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse3">
         تنظیمات کاربر
        </a>
      </h4>
    </div>
	 <div id="collapse3" class="panel-collapse collapse" role="tabpane3" aria-labelledby="heading3">
   <div class="panel-body">
		<ul id="dropdown-user-menu">
			<li><a href="#"><i class="fa fa-unlock-alt" aria-hidden="true"></i>تغییر کلمه عبور</a></li>
			<li><a href="#"><i class="fa fa-envelope-o" aria-hidden="true"></i>تغییر ایمیل</a></li>
			<li><a href="#"><i class="fa fa-sign-out" aria-hidden="true"></i>خروج</a></li>
			
		</ul>
      </div>
	  </div>
  </div>
</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-10 col-md-pull-2 col-sm-9 col-sm-pull-3 main-box-content-mp">
				<div class="title-box-content-mp">
					<h3>ویرایش اطلاعات</h3>
				</div>
				<div class="body-box-content-mp">
					<iframe name="xFrame" id="xFrame" style="width:100%;height:300px;" src=<?php echo $website; ?>>Web Master Panel</iframe>
				</div>
			</div>
				
		</div>
		</div>
	</section>
	<footer>
	<section class="footer-main-sec">
	<div class="container">
		
			<div class="row">

				<div class="col-xs-12">
					<article class="copyright">
						<p>کلیه حقوق این سیستم متعلق به میوگرام می باشد</p>
					</article>

				</div>
				
			</div>
		
	</div>
	</section>

	</footer>

</div>
	
</body>
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/ct-paper-radio.js"></script>
   <script src="js/ct-paper-checkbox.js"></script>
  <script>
		$(document).ready(function(){
			
			
			var dc_whidth=$(document).width();
			if(dc_whidth<768){
				
				$(".logo-menu li img").css("width","50px");
				$(".login-menu li a span").remove();
				$(".login-menu li").css({"padding-right":"0","padding-left":"0"});
				$(".login-menu").css("padding","0");
				$(".panel-right-main-box").css("margin-top","10px");
				$("#collapseTwo").removeClass("in");
				$(".main-box-content-mp").css({"margin-top":"0","margin-bottom":"10px"});
				$(".panel-right-main-box .panel").css("margin-bottom","10px");
				$(".box-footer").css({"text-align":"center","border-bottom":"1px solid #d4db95","padding-top":"20px","padding-bottom":"20px"});
				$(".contact-footer-main-box").css({"border-bottom":"none"})
			}
		});
	</script>
	
</html>