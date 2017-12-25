<?php

class App 
{
    /**
     * @var DB
     */
    private $mysqldb;

    /**
     * App constructor.
     * @param DB $mysqldb
     */
    public function __construct(DB $mysqldb)
    {

        $this->mysqldb = $mysqldb;
    }
}
