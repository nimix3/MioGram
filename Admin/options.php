<?php
require_once('../Robot/Library/basement/iniparser.class.php');
require_once('../Robot/Library/basement/jdf.php');
$plug = $_REQUEST['plugin'].".dat";
if($_SERVER['REQUEST_METHOD'] == 'POST' and isset($plug) and !empty($plug))
{
	if(file_exists("../Robot/Plugins/Hooks/".$plug))
	{
		$items = parse_ini_file("../Robot/Plugins/Hooks/".$plug);
		if(is_array($items))
		{
			foreach($_POST as $name => $value)
			{
				if($name == "plugin")
					continue;
				if(isset($value) and !empty($value))
				{
					if($value == ".")
						$items[$name] = "";
					else
						$items[$name] = $value;
				}
			}
			write_ini_file($items, "../Robot/Plugins/Hooks/".$plug);
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
if(file_exists("../Robot/Plugins/Hooks/".$plug))
{
	$items = parse_ini_file("../Robot/Plugins/Hooks/".$plug);
}
else
{
	$msg = "در ثبت اطلاعات مشکلی پیش آمد";
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
						if($name == "plugin")
							continue;
						echo '<label for="x'.$var.'" class="form-group plugin-label">'.$var.'</label>
							  <textarea id="x'.$var.'" class="form-control plugin-input" rows="'.(intval(substr_count($val,PHP_EOL))+(1)).'" name="'.$var.'" placeholder="'.$var.'">'.$val.'</textarea>';
					}
				}
			?>
            </div>
        </div>
        <div class=" col-md-6 col-md-offset-3 ">

            <div class="form-group">

				<input type="hidden" value="<?php echo $_REQUEST['plugin']; ?>" name="plugin">
                <button type="submit" class="btn btn-default">ثبت اطلاعات</button>

            </div>
        </div>

    </div>

</form>
</body>
</html>