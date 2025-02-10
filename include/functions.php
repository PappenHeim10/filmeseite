<?php
namespace mvc;


function write_error($param)
{
	$_SESSION['error'] = $param;
}

function read_error()
{
	if(isset($_SESSION['error']))
	{
		$error = $_SESSION['error'];
		unset($_SESSION['error']);
		return $error;
	}
}

function read_message()
{
	if(isset($_SESSION['message']))
	{
		echo $_SESSION['message'];
		unset($_SESSION['message']);
	}
}

function autoloadNS(string $param) 
{
	$arr = explode("\\", $param);
	$className = $arr[count($arr) - 1];
	
	if (file_exists("models/$className.php")) 
	{
		require_once "models/$className.php";
	}
}

spl_autoload_register('autoloadNS');






?>