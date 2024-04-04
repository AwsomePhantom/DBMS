<?php

const HOST = 'localhost';
const DB = 'dbmsproject';
const USER = 'root';
const PASS = 'root';
const CHARSET = 'utf8mb4';
const CONN_STR = 'mysqli:host=$host;db=$db;charset=$charset';
const OPT = array(
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION],
    [PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH]
);



class AdminConnection {
    private $pdo = null;
    function __construct() {
        try {
            $this->pdo = new PDO(CONN_STR, USER, PASS, OPT);
        }
        catch (PDOException $e) {
            throw new PDOException($e->getMessage(), $e->getCode());
        }
    }


}