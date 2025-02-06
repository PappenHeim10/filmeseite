<?php
namespace mvc1;;
session_start();
require_once "includes/functions.php";
require_once "includes/datenbank.php";
require_once "includes/head.php";
?>
<body>
<div id="wrapper">
<?php
require_once "includes/header.php";
require_once "includes/navi.php";
?>
<main>
<div id="error">
<?php
echo read_error();
?>
</div>

<div id="message">
<?php
read_message();
?>
</div>

<?php
$whitelist = ['seite1', 'seite2',  'seite3',  'index',  'agb',  'home1'];

$view = isset($_REQUEST['action'])?$_REQUEST['action']:'start';


if(in_array($view, $whitelist))
{
	switch($view)
	{
		case 'seite1':
		#require_once "views/$view.php";
		break;
		case 'seite2':
		#require_once "views/$view.php";
		break;
		case 'seite3':
		#require_once "views/$view.php";
		break;
		case 'agb':
		#require_once "views/$view.php";
		break;	
		default:
		
		break;
	}
	if($view =='home')
	{
		require_once "home.php";
	}
	else
	{
		require_once "views/$view.php";
	}
}
else
{
	write_error('Seite nicht gefunden.');
	require_once "home.php";
}
?>

</main>
<?php
require_once "includes/footer.php";
?>