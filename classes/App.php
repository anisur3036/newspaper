<?php

class App
{
	protected $db;
    public function __construct()
    {
		$this->db = new DB();
    }
}
