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
/*
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
*/

    define("URI", "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    function resetPost() {
        unset($_POST);
        header("Location: " . URI);
    }

    $testLogin = file_get_contents($root_dir . '/test/testLogin.html');
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['user']) && isset($_POST['pass'])) {
            $user = $_POST['user'];
            $pass = $_POST['pass'];
            try {
                $user = $conn->login($user, $pass);
                $sql = "SELECT name FROM customers_info WHERE id = \"{$user->}\"";
            }
            catch (PDOException $e) {
                throw new PDOException($e->getMessage(), $e->getCode());
            }
            catch (Exception $e) {
                echo 'Error: ' . $e->getMessage() . '<br>';
            }
        }
    }

    echo $testLogin;    // publish the web page




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

        /**
         * @throws Exception
         */
        function login(string $username, string $password) : ?user {
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

            $sql = 'SELECT id, username, customer_id, business_id, last_modified, last_logged, registration_date FROM user_accounts WHERE username = ? AND password = ? LIMIT 1;';
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$username, $password]);
            if($stmt->fetchColumn() == 0) {
                return null;
            }
            $user_row = $stmt->fetch();     // get user_account row

            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);

            $sql = 'SELECT * from customers_info LIMIT 1';
            $stmt = $this->pdo->query($sql);
            if($stmt->fetchColumn() == 0) {
                throw new Exception('The username does not have a customers_info entry associated');
            }
            $customer_row = $stmt->fetch();  // get customer's row

            $customer_id = $user_row['customer_id'];
            $business_id = $user_row['business_id'];

            $person = new customer(
                $customer_row[0],                                   // id
                $customer_row[1],                                   // name
                $customer_row[2],                                   // lastname
                new DateTime($customer_row[3]),                     // birthdate
                $customer_row[4],                                   // gender
                $this->get_customer_contacts($customer_id),         // phone numbers
                $this->get_customer_address($customer_id));         // gender

            return $user;
        }

        function create_user(user $user, string $password) : bool {
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
            if(!$res) return false;
            $this->pdo->commit();
            return true;
        }

        // Customer Info registration, function called from create_user()
        // Transaction methods not included
        function register_customer(customer $c) : bool {
            // Register customer
            // Register phone numbers
            // Register addresses

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
            if(!$res) return false;

            $sql = 'INSERT INTO customers_contacts (customer_id, phone) VALUES (?, ?);';
            $stmt = $this->pdo->prepare($sql);
            foreach($c->contacts->phones as $x) {
                // stmt->execute() returns back a bool
                $res = $stmt->execute([
                  $c->id,
                  $x
                ]);
            }
            if(!$res) return false;

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
            if(!$res) return false;
            return true;
        }

        // Business Info registration, function called from create_user()
        // Transaction methods not included
        function register_business(business $b) : bool {
            // Register business
            // Register phone numbers
            // Register addresses

            // Insert first business, address and contacts are weak entities that are dependent on business_id

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
            if(!$res) return false;

            $sql = 'INSERT INTO businesses_contacts (business_id, phone) VALUES (?, ?);';
            $stmt = $this->pdo->prepare($sql);
            foreach($b->contacts->phones as $x) {
                // stmt->execute() returns back a bool
                $res = $stmt->execute([
                    $b->id,
                    $x
                ]);
            }
            if(!$res) return false;

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

            if(!$res) return false;
            return true;
        }

        function get_customer_address(string $customer_id) : ?address {
            $sql = "SELECT * FROM customers_addresses WHERE customer_id = \"{$customer_id}\" LIMIT 1;";
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
            $stmt = $this->pdo->query($sql);
            if($stmt) {
                $row = $stmt->fetch();
                return new address($row[0], $row[2], $row[3]. $row[4], $row[5], $row[6], $row[7]);
            }
            return false;
        }

        function get_business_address(string $business_id) : ?address {
            $sql = "SELECT * FROM businesses_addresses WHERE business_id = \"{$business_id}\" LIMIT 1;";
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
            $stmt = $this->pdo->query($sql);
            if($stmt) {
                $row = $stmt->fetch();
                return new address($row[0], $row[2], $row[3]. $row[4], $row[5], $row[6], $row[7]);
            }
            return false;
        }

        function get_customer_contacts(string $customer_id) : ?contacts {
            $sql = "SELECT * FROM customers_contacts WHERE customer_id = \"{$customer_id}\"";
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
            $stmt = $this->pdo->query($sql);
            if($stmt) {
                $contacts = new contacts($customer_id, []);
                while($row = $stmt->fetch()) {
                    $contacts->phones[] = $row[2];
                }
                return $contacts;
            }
            return false;
        }

        function get_business_contacts(string $business_id) : ?contacts {
            $sql = "SELECT * FROM businesses_contacts WHERE business_id = \"{$business_id}\"";
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
            $stmt = $this->pdo->query($sql);
            if($stmt) {
                $contacts = new contacts($business_id, []);
                while($row = $stmt->fetch()) {
                    $contacts->phones[] = $row[2];
                }
                return $contacts;
            }
            return false;
        }

        // Generates a unique ID by probing the db
        function generateID() : ?string {
            $unique_id = null;
            $sql = 'SELECT id FROM '.
                '(SELECT id FROM customers_info AS A '.
                'UNION ALL '.
                'SELECT id FROM businesses_info AS B ' .
                'UNION ALL ' .
                'SELECT id FROM user_accounts) AS derived ' .
                'WHERE id = ?;';
            $exists = 1;
            while ($exists > 0) {    // check if random id already exists
                $unique_id = substr(md5(uniqid(mt_rand(), true)), 0, 8);       // assign a new random id
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$unique_id]);
                $exists = $stmt->rowCount();
            }

            return $unique_id;
        }

        function update_user_account(user $user, ?string $password) : bool {
            if($password != null) {     // update only password
                $sql = 'UPDATE user_accounts SET password = ?, last_logged = NOW() WHERE id = ?;';
                $stmt = $this->pdo->prepare($sql);
                $result = $stmt->execute([$password, $user->id]);
            }
            else {      // for now, update only the username
                $sql = 'UPDATE user_accounts SET username = ?, last_modified = NOW();';
                $stmt = $this->pdo->prepare($sql);
                $result = $stmt->execute([$user->username]);
            }
            return $result;
        }

        // Clone customer and pass it by reference, once info updated assign it to the main object
        function update_customer_info(customer $c) : bool {
            $sql = 'UPDATE customers_info SET name = ?, lastname = ?, birthdate = ?, gender = ? WHERE id = ?;';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$c->name, $c->lastname, $c->birthdate, $c->birthdate, $c->gender, $c->id]);
        }

        // Clone customer and pass it by reference, once info updated assign it to the main object
        function update_business_info(business $b) : bool {
            $sql = 'UPDATE businesses_info SET company_name = ?, company_type = ?, licence_number = ?, office_hour_start = ?, office_hour_end = ?, office_weekdays = ? WHERE id = ?;';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$b->company_name, $b->company_type, $b->licence_number, $b->start.format('H:i:s'), $b->end.format('H:i:s'), $b->weekdays, $b->id]);
        }

        // Clone customer and pass it by reference, once info updated assign it to the main object
        function update_customer_address(string $customer_id, address $a) : bool {
            $sql = 'UPDATE customers_addresses SET country_code = ?, city = ?, district = ?, street = ?, holding = ?, notes = ? WHERE customer_id = ?;';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$a->country_code, $a->city, $a->district, $a->street, $a->holding, $a->notes, $customer_id]);
        }

        // Clone customer and pass it by reference, once info updated assign it to the main object
        function update_business_address(string $business_id, address $a) : bool {
            $sql = 'UPDATE businesses_addresses SET country_code = ?, city = ?, district = ?, street = ?, holding = ?, notes = ? WHERE business_id = ?;';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$a->country_code, $a->city, $a->district, $a->street, $a->holding, $a->notes, $business_id]);
        }

        function add_customer_contact(string $customer_id, string $phone) : bool {
            $sql = 'INSERT INTO customers_contacts (customer_id, phone) VALUES (?, ?);';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$customer_id, $phone]);
        }

        function add_business_contact(string $business_id, string $phone) : bool {
            $sql = 'INSERT INTO businesses_contacts (business_id, phone) VALUES (?, ?);';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$business_id, $phone]);
        }

        function remove_customer_contact(string $customer_id, string $phone) : bool {
            $sql = 'DELETE FROM customers_contacts WHERE customer_id = ? AND phone = ?;';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$customer_id, $phone]);
        }

        function remove_business_contact(string $business_id, string $phone) : bool {
            $sql = 'DELETE FROM businesses_contacts WHERE business_id = ? AND phone = ?;';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$business_id, $phone]);
        }

        // destructor
        function __destruct() {
            $this->pdo = null;
        }

        // member variables
        private ?PDO $pdo = null;
    }