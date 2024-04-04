<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    require_once $root_dir . '/classes/contacts.php';
    require_once $root_dir . '/classes/address.php';
    require_once $root_dir . '/classes/person.php';
    require_once $root_dir . '/classes/business.php';

    use classes\contacts;
    use classes\address;
    use classes\person;
    use classes\business;

    $ROOT_DIR =  $_SERVER['DOCUMENT_ROOT'];
    require_once ($ROOT_DIR.'/database/variables.php');



    class connection {

        function connect() : void {
            $str = 'mysql:host='.CONN_INFO['HOST'].';dbname='.CONN_INFO['DBNAME'].';charset=utf8mb4;';
            $opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH];
            try {
                $this->pdo = new PDO($str, CONN_INFO['USERNAME'], CONN_INFO['PASSWORD']);
                echo 'DB connected.<br>';
            }
            catch (PDOException $e) {
                throw new PDOException($e->getMessage(), $e->getCode());
            }
        }

        function register_person(person $p) : bool {
            /*
             * Begin transaction
             * register phone numbers
             * register addresses
             * register person
             * end transaction
             */

            try {
                $this->pdo->query('BEGIN');
                $sql = 'INSERT INTO contacts VALUES(null, ?, ?)';
                $stmt = $this->pdo->prepare($sql);
                foreach($p->contacts->phones as $x) {
                    // stmt->execute() returns back a bool
                    $stmt->execute([
                      $p->id,
                      $x
                    ]);
                }

                $addr = $p->address;
                $sql = 'INSERT INTO address VALUES (null, ?, ?, ?, ?, ? ,?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute(

                );

                $this->pdo->query('COMMIT');
            }
            catch (PDOException $e) {
                throw new PDOException($e->getMessage(), $e->getCode());
            }
            return false;
        }

        function __destruct() {
            $this->pdo = null;
        }

        private ?PDO $pdo = null;
    }