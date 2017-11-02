<?php
class Url
{
	public static $page = "page";
	public static $folder = PAGES_DIR;
	public static $params = array();

    public static function getParam($par) 
    {
		return isset($_GET[$par]) && $_GET[$par] != "" ? $_GET[$par] : NULL;
	}

	public static function cPage() 
	{
		return isset($_GET[static::$page]) ?
				$_GET[static::$page] : 'index';
	}

	public static function getPage() 
	{
		$page = static::$folder . DS . static::cPage() . ".php";
		$error = static::$folder . DS . "error.php";
		return is_file($page) ? $page : $error;
	}

	public static function getAll() 
	{
		if (!empty($_GET)) {
			foreach($_GET as $key => $value) {
				if (!empty($value)) {
					static::$params[$key] = $value;
				}
			}
		}
	}

	public static function getCurrentUrl($remove = null)
	{
		static::getAll();
		$out = array();
		if (!empty($remove)) {
			$remove = !is_array($remove) ? array($remove) : $remove;
			foreach (static::$params as $key => $value) {
				if (in_array($key, $remove)) {
					unset(static::$params[$key]);
				}
			}
		}
		foreach (static::$params as $key => $value) {
			$out[] = $key . "=" . $value;
		}
		return "/?" . implode("&", $out);
	}
}
