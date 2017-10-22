<?php
require_once('config.php');

function __autoload($class_name) {
	$class = explode("_", ucfirst($class_name));
	$path = implode("/", $class).".php";
	require_once($path);
}