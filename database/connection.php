<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    require_once $root_dir . '/classes/contacts.php';
    require_once $root_dir . '/classes/address.php';
    require_once $root_dir . '/classes/customer.php';
    require_once $root_dir . '/classes/business.php';

    use classes\contacts;
    use classes\address;
    use classes\customer;
    use classes\business;

    $ROOT_DIR =  $_SERVER['DOCUMENT_ROOT'];
    require_once ($ROOT_DIR.'/database/variables.php');

    $conn = new connection();
    $conn->connect();
    $c = new contacts(null, 2, ['884-446-664', '844-124-336'], false);
    $a = new address(null, 2, 'ABJ', 'DOHA', 'SINAI', 'FLEET STREET', '16', 'Near the pharmacy', false);
    $p = new customer(null, 'shakil', 'hasan', '1990-05-06', 'M', $c, $a);
    $conn->register_customer($p);


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

        function register_customer(customer $p) : bool {
            /*
             * start the transaction
             * register customer
             * register phone numbers
             * register addresses
             * end the transaction
             */

            try {
                $this->pdo->beginTransaction();
                // insert first customer, address and contacts are weak entities dependents on

                $sql = 'INSERT INTO customer (name, lastname, birthdate, gender) VALUES (?, ?, ?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $res = $stmt->execute([
                    $p->name,
                    $p->lastname,
                    $p->birthdate,
                    $p->gender[0]
                ]);

                $sql = 'INSERT INTO contacts VALUES(null, ?, ?, null)';
                $stmt = $this->pdo->prepare($sql);
                foreach($p->contacts->phones as $x) {
                    // stmt->execute() returns back a bool
                    $res = $stmt->execute([
                      $p->id,
                      $x
                    ]);
                }

                $addr = $p->address;
                $sql = 'INSERT INTO address VALUES (null, ?, ?, ?, ?, ? ,?, ?, null)';
                $stmt = $this->pdo->prepare($sql);
                $res = $stmt->execute([
                    $p->id,
                    $addr->country_code,
                    $addr->city,
                    $addr->district,
                    $addr->street,
                    $addr->holding,
                    $addr->notes
                ]);

                $this->pdo->commit();
                return true;
            }
            catch (PDOException $e) {
                $this->pdo->rollBack();
                throw new PDOException($e->getMessage(), $e->getCode());
            }
            return false;
        }

        function register_business(business $b) : bool {
            /*
             * start the transaction
             * register customer
             * register phone numbers
             * register addresses
             * end the transaction
             */

            try {
                $this->pdo->query('BEGIN');

                $sql = 'INSERT INTO business VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, NOW())';
                $stmt = $this->pdo->prepare($sql);
                $res = $stmt->execute([
                    $b->id,
                    $b->company_name,
                    $b->company_type,
                    $b->licence_number,
                    $b->start,
                    $b->end,
                    $b->weekdays,
                    $b->active
                ]);
                if(!$res) return false;

                $sql = 'INSERT INTO contacts VALUES(null, ?, ?, true)';
                $stmt = $this->pdo->prepare($sql);
                foreach($b->contacts->phones as $x) {
                    // stmt->execute() returns back a bool
                    $res = $stmt->execute([
                        $b->customer->id,
                        $x
                    ]);
                    if(!$res) return false;
                }

                $addr = $b->customer->address;
                $sql = 'INSERT INTO address VALUES (null, ?, ?, ?, ?, ? ,?, ?, true)';
                $stmt = $this->pdo->prepare($sql);
                $res = $stmt->execute([
                    $b->customer->id,
                    $addr->country_code,
                    $addr->city,
                    $addr->district,
                    $addr->street,
                    $addr->holding,
                    $addr->notes
                ]);
                if(!$res) return false;


                $this->pdo->query('COMMIT');
                return true;
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