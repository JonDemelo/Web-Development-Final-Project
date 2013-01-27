<?php 
require_once('./webroot.conf.php');

$page=process_script();

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" xml:lang="en-US" lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Jonathan Demelo | CS3336 Project</title>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>
<link rel="stylesheet" href="./css/prj.css" type="text/css" media="screen" />
<link rel="stylesheet" href="http://code.jquery.com/ui/1.9.2/themes/base/jquery-ui.css" type="text/css" media="all" />
</head>

<body>

<?php echo $page; ?>

<script type="text/javascript">
	$(function() {
        $("#datepicker").datepicker({
        	yearRange: "-200:+0", 
            changeMonth: true,
            changeYear: true,
            dateFormat: 'mm-dd-yy'
        });
    });
</script>

</body>
</html>
