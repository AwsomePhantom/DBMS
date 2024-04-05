<?php
    $root_dir = $_SERVER['DOCUMENT_ROOT'];
    require_once $root_dir . '/classes/contacts.php';
    require_once $root_dir . '/classes/address.php';
    require_once $root_dir . '/classes/customer.php';
    require_once $root_dir . '/classes/business.php';
    require_once $root_dir . '/classes/user.php';

    use classes\contacts;
    use classes\address;
    use classes\customer;
    use classes\business;
    use classes\user;

    $ROOT_DIR =  $_SERVER['DOCUMENT_ROOT'];
    require_once ($ROOT_DIR.'/database/variables.php');

    $conn = new connection();
    $conn->connect();

    // Sample code for user registration
    $unique_id = $conn->generateID();
    $phones = new contacts(null, ['888-444-5555', '112-354-9477']);
    $phones_company = new contacts(null, ['0694-447-9994', '0466-9974-4444']);
    $house_address = new address(null, 'AJB', 'DOHA', 'Kerala', 'Crooker\'s Steet', '48', 'Near the plaza');
    $office_address = new address(null, 'AJB', 'DOHA', 'Village', 'Flat Steet', '20', 'On the top of the hill, beside the supermarket');

    $person = new customer($unique_id, 'rabinul', 'islam', '1992-11-10', 'M', $phones, $house_address);
    $unique_id = $conn->generateID();
    $company = new business($unique_id, $person, 'Fast Tyres', 'Auto Mechanic', 'VXPT-CBBPO-AV1566',
        $phones_company, $office_address, new DateTime('08:30:00'), new DateTime('20:00:00'),
        'SUN,MON,TUE,WED,THU,FRI', null, true);
    $unique_id = $conn->generateID();
    $user = new user($unique_id, 'rabinul', $person, $company, null, null, null);

    $result = $conn->create_user($user, '1234');
    if(!$result) {
        echo 'Failed to create new user.<br>';
    }
    else {
        echo 'New user successfully created.<br>';
    }



    /* Main MYSQL connection and query class */
    // add to the user class query functionalities for navigation
    class connection {

        /* Initialiase the object and connect manually */
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

        function create_user(user $user, string $password) : bool {
            try {
                // Username is unique, check names in the form before creating the object
                $this->pdo->beginTransaction();

                if($user->business) {
                    $res = $this->register_customer($user->customer);   // insert customer details into the db
                    if(!$res) return false;
                    $res = $this->register_business($user->business);   // insert business details into the db
                    if(!$res) return false;

                    $sql = 'INSERT INTO user_accounts (id, username, password, customer_id, business_id) VALUES (?, ?, ?, ?, ?)';
                    $stmt = $this->pdo->prepare($sql);
                    $res = $stmt->execute([
                        $user->id,
                        $user->username,
                        $password,
                        $user->customer->id,
                        $user->business->id
                    ]);
                }
                else {
                    $res = $this->register_customer($user->customer);   // insert customer details into the db
                    if(!$res) return false;

                    $sql = 'INSERT INTO user_accounts (id, username, password, customer_id) VALUES (?, ?, ?, ?)';
                    $stmt = $this->pdo->prepare($sql);
                    $res = $stmt->execute([
                        $user->id,
                        $user->username,
                        $password,
                        $user->customer->id,
                    ]);
                }

                $this->pdo->commit();
                return true;
            }
            catch (PDOException $e) {
                throw new PDOException($e->getMessage(), $e->getCode());
            }
            return false;
        }

        // Customer Info registration, function called from create_user()
        // Transaction methods not included
        function register_customer(customer $c) : bool {
            /*
             * register customer
             * register phone numbers
             * register addresses
             */

            try {
                // insert first customer, address and contacts are weak entities dependents on customer_id

                $sql = 'INSERT INTO customers_info (id, name, lastname, birthdate, gender) VALUES (?, ?, ?, ?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $res = $stmt->execute([
                    $c->id,
                    $c->name,
                    $c->lastname,
                    $c->birthdate,
                    $c->gender[0]
                ]);

                $sql = 'INSERT INTO customers_contacts (customer_id, phone) VALUES (?, ?);';
                $stmt = $this->pdo->prepare($sql);
                foreach($c->contacts->phones as $x) {
                    // stmt->execute() returns back a bool
                    $res = $stmt->execute([
                      $c->id,
                      $x
                    ]);
                }

                $addr = $c->address;
                $sql = 'INSERT INTO customers_addresses (customer_id, country_code, city, district, street, holding, notes) VALUES (?, ?, ?, ?, ? ,?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $res = $stmt->execute([
                    $c->id,
                    $addr->country_code,
                    $addr->city,
                    $addr->district,
                    $addr->street,
                    $addr->holding,
                    $addr->notes
                ]);

                return true;
            }
            catch (PDOException $e) {
                $this->pdo->rollBack();
                throw new PDOException($e->getMessage(), $e->getCode());
            }
            return false;
        }

        // Business Info registration, function called from create_user()
        // Transaction methods not included
        function register_business(business $b) : bool {
            /*
             * register business
             * register phone numbers
             * register addresses
             */

            try {
                // insert first business, address and contacts are weak entities that are dependent on business_id

                $sql = 'INSERT INTO businesses_info(id, owner_id, company_name, company_type, licence_number, office_hour_start, office_hour_end, office_weekdays) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $res = $stmt->execute([
                    $b->id,
                    $b->customer->id,
                    $b->company_name,
                    $b->company_type,
                    $b->licence_number,
                    $b->start->format('H:i:s'),
                    $b->end->format('H:i:s'),
                    $b->weekdays
                ]);

                $sql = 'INSERT INTO businesses_contacts (business_id, phone) VALUES (?, ?);';
                $stmt = $this->pdo->prepare($sql);
                foreach($b->contacts->phones as $x) {
                    // stmt->execute() returns back a bool
                    $res = $stmt->execute([
                        $b->id,
                        $x
                    ]);
                }

                $addr = $b->address;
                $sql = 'INSERT INTO businesses_addresses (business_id, country_code, city, district, street, holding, notes) VALUES (?, ?, ?, ?, ? ,?, ?)';
                $stmt = $this->pdo->prepare($sql);
                $res = $stmt->execute([
                    $b->id,
                    $addr->country_code,
                    $addr->city,
                    $addr->district,
                    $addr->street,
                    $addr->holding,
                    $addr->notes
                ]);

                return true;
            }
            catch (PDOException $e) {
                $this->pdo->rollBack();
                throw new PDOException($e->getMessage(), $e->getCode());
            }
            return false;
        }

        // Generate unique ID by probing the DB
        function generateID() : ?string {
            $unique_id = null;
            $sql = 'SELECT id FROM '.
                '(SELECT id FROM customers_info AS A '.
                'UNION ALL '.
                'SELECT id FROM businesses_info AS B ' .
                'UNION ALL ' .
                'SELECT id FROM user_accounts) AS derived ' .
                'WHERE id = ?;';
            try {
                $exists = 1;
                while ($exists > 0) {    // check if random id already exists
                    $unique_id = substr(md5(uniqid(mt_rand(), true)), 0, 8);       // assign a new random id
                    $stmt = $this->pdo->prepare($sql);
                    $stmt->execute([$unique_id]);
                    $exists = $stmt->rowCount();
                }
            }
            catch (PDOException $e) {
                throw new PDOException($e->getMessage(), $e->getCode());
                return null;
            }

            return $unique_id;
        }

        function __destruct() {
            $this->pdo = null;
        }

        private ?PDO $pdo = null;
    }