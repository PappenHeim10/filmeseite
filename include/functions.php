<?php

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
		$message = $_SESSION['message'];
		unset($_SESSION['message']);
		return $message;
	}
}
function write_message($param)
{
	$_SESSION['message'] = $param;
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