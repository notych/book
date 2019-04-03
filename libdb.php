<?php

class DbPDO
{
    private $db;

    public function __construct()
    {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $opt = [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC];
        $this->db = new PDO($dsn, DB_USER, DB_PASSWORD, $opt);
        $this->db->exec("set names utf8");

    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function fetch($result)
    {
        return $result->fetch();
    }

    public function exec($sql)
    {
        return $this->db->exec($sql);
    }

    public function lastInsertId()
    {
       return $this->db->lastInsertId();
    }

}

class Dbmysqli
{
    private $db;

    public function __construct()
    {
        $this->db = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        $this->db->set_charset("utf-8");


    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function fetch($result)
    {
        return $result->fetch_assoc();
    }

    public function exec($sql)
    {
        return $this->db->real_query($sql);
    }

    public function lastInsertId()
    {
        return $this->db->insert_id;
    }

}