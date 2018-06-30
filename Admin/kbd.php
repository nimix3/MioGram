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
require_once('../Robot/Library/basement/iniparser.class.php');
require_once('../Robot/Library/basement/jdf.php');
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if(file_exists("../Robot/Storage/KBD.db"))
	{
		$items = parse_ini_file("../Robot/Storage/KBD.db");
		if(is_array($items))
		{
			foreach($_POST as $name => $value)
			{
				if($name == "passlock")
					continue;
				if(isset($value) and !empty($value))
				{
					if($value == ".")
						$items[$name] = "";
					else
						$items[$name] = $value;
				}
			}
			write_ini_file($items, "../Robot/Storage/KBD.db");
			$msg = "تنظیمات ذخیر شد";
		}
		else
		{
			$msg = "در ثبت اطلاعات مشکلی پیش آمد";
		}
	}
	else
	{
		$msg = "در ثبت اطلاعات مشکلی پیش آمد";
	}
}
if(file_exists("../Robot/Storage/KBD.db"))
{
	$items = parse_ini_file("../Robot/Storage/KBD.db");
}
else
{
	$msg = "در بارگذاری اطلاعات مشکلی پیش آمد";
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

<form action="" method="POST" enctype="multipart/form-data" accept-charset="utf-8" target="_self" class="form-horizontal form-plugin">

    <div class="row">
        <div class=" col-md-6 col-md-offset-3 ">

            <legend class="plugin-legend"><?php echo $_REQUEST['plugin']; ?></legend>
			<h4 class="message"><?php echo $msg; ?></h4>
        </div>
        <div class=" col-md-6 col-md-offset-3 ">

            <div class="form-group">
			<?php
				if(is_array($items))
				{
					foreach($items as $var => $val)
					{
						if($var == "passlock")
							continue;
						echo '<label for="x'.$var.'" class="form-group plugin-label">'.$var.'</label>
							  <textarea id="x'.$var.'" class="form-control plugin-input" rows="1" name="'.$var.'" placeholder="'.$var.'">'.$val.'</textarea>';
					}
				}
			?>
            </div>
        </div>
        <div class=" col-md-6 col-md-offset-3 ">

            <div class="form-group">
				<input type="hidden" value="26221759" name="passlock">
                <button type="submit" class="btn btn-default">ثبت اطلاعات</button>

            </div>
        </div>

    </div>

</form>
</body>
</html>