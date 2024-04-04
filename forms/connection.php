<?php
    class connection {

        function connect() {
            $str = 'mysql:host='.self::CONN_INFO['HOST'].';dbname='.self::CONN_INFO['DBNAME'].'charset=utf8mb4;';
            try {
                $this->pdo = new PDO($str, self::CONN_INFO['USERNAME'], self::CONN_INFO['PASSWORD']);
            }
            catch (PDOException $e) {
                new PDOException($e->getMessage(), $e->getCode());
            }
        }

        function __destruct() {
            $this->pdo = null;
        }

        private ?PDO $pdo = null;
        private const CONN_INFO = array(
            'HOST' => 'localhost',
            'DBNAME' => 'test',
            'USERNAME' => 'root',
            'PASSWORD' => 'root',
            'PORT' => 3306
        );
    }

    class Person {

    }