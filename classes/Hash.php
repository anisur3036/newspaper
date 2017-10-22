<?php
class Hash {
	public static function make($string, $salt = '')
	{
		return hash('sha256', $string . $salt);
	}


	public static function salt($length)
	{
		return mcrypt_create_iv($length);
	}


	public static function unique()
	{
		return self::make(uniqid());
	}
	
	public function bcrypt($password, $options=[12])
	{
		return password_hash(Input::get($password), PASSWORD_BCRYPT, $options);
	}
}
