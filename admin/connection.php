<?php
//require_once $_SERVER['DOCUMENT_ROOT'] . '/users.php'; // add user, business class definition
require_once '../users.php';
echo 'user.php added.<br>';

static $database = new Connection();

class Connection {
    private ?PDO $conn = null;
    private const CONN_PARAM = array(
        'HOST' => 'localhost',
        'USERNAME' => 'root',
        'PASSWORD' => 'root',
        'DATABASE' => 'dbmsproject',
        'PORT' => 3306
    );

    function connect() : void
    {
        $conn_string = $this->connection_string();
        try {
            $conn = new PDO($conn_string, self::CONN_PARAM['USERNAME'], self::CONN_PARAM['PASSWORD'],
                            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            echo 'Connected to the database.<br>';
        }
        catch (PDOException $exception) {
            echo 'Failed to connect to the database.<br>Exception: ' . $exception->getMessage();
        }
    }

    function create_user(User &$user) : bool {
        return false;
    }

    function connection_string() : string {
        // interpolation with constants -> echo 'Value: {$K(CONSTANT_NAME)}
        return 'mysql:host=' . self::CONN_PARAM['HOST'] .
            ';dbname=' . self::CONN_PARAM['DATABASE'] .
            ';CHARSET=UTF8';
    }

    function abort(): void
    {
        $conn = null;
        echo 'Connection Aborted.<br>';
    }

    function __destruct() {
        $conn = null;
    }
}