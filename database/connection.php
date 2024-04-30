<?php
	require_once ROOT_DIR . '/classes/contacts.php';
	require_once ROOT_DIR . '/classes/address.php';
	require_once ROOT_DIR . '/classes/customer.php';
	require_once ROOT_DIR . '/classes/business.php';
	require_once ROOT_DIR . '/classes/user.php';

	use classes\contacts;
	use classes\address;
	use classes\customer;
	use classes\business;
	use classes\user;

	require_once (ROOT_DIR . '/database/variables.php');

	const CONNECTION = new connection();
	CONNECTION->connect();
/*
	// Sample code for user registration

	$unique_id = CONNECTION->generateID();
	$phones = new contacts(null, ['888-444-5555', '112-354-9477']);
	$phones_company = new contacts(null, ['0694-447-9994', '0466-9974-4444']);
	$house_address = new address(null, 'AJB', 'DOHA', 'Kerala', '1204', 'Crooker\'s Steet', '48', 'Near the plaza');
	$office_address = new address(null, 'AJB', 'DOHA', 'Village', '1204', 'Flat Steet', '20', 'On the top of the hill, beside the supermarket');

	$person = new customer($unique_id, 'rabinul', 'islam', '1992-11-10', 'M', $phones, $house_address);
	$unique_id = CONNECTION->generateID();
	$company = new business($unique_id, $person, 'Fast Tyres', 'Auto Mechanic', 'VXPT-CBBPO-AV1566',
		$phones_company, $office_address, new DateTime('08:30:00'), new DateTime('20:00:00'),
		'SUN,MON,TUE,WED,THU,FRI', null, true);
	$unique_id = CONNECTION->generateID();
	$user = new user($unique_id, 'rabinul', $person, $company, null, null, null);

	$result = CONNECTION->create_user($user, '1234');
	if(!$result) {
		echo 'Failed to create new user.<br>';
	}
	else {
		echo 'New user successfully created.<br>';
	}
*/

/**
 * Get the current browser URI http or https
 * @return string
 */
    function getURI() : string {
        $ssl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' || $_SERVER['SERVER_PORT'] == 433);
        $out = $ssl ? "https://" : "http://";
        $out .= $_SERVER['HTTP_HOST'];
        $out .= $_SERVER['REQUEST_URI'];
        // https are null because a configuration in httpd
        return $out;
    }

	/**
     * Main MYSQL connection and query class
	 * todo: add to the user class query functionalities for, or add it to user object passing pdo as param
     */
	class connection {

        /**
         * Initialize the pdo object and connect manually
         * @return void
         */
        function connect() : void {
			$str = 'mysql:host='.CONN_INFO['HOST'].';dbname='.CONN_INFO['DBNAME'].';charset=utf8mb4;';
			$opts = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH];
			try {
				$this->pdo = new PDO($str, CONN_INFO['USERNAME'], CONN_INFO['PASSWORD']);
				// connected
			}
			catch (PDOException $e) {
				throw new PDOException($e->getMessage(), $e->getCode());
			}
		}

        /**
         * Function used to log in into the webpage
         * Registers into the database user token for the session
         * @param string $username
         * @param string $password
         * @return user|null
         * @throws Exception
         */
		function login(string $username, string $password) : ?user {
			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

			/*    Find Username in the database    */
			$sql = 'SELECT id, username, customer_id, business_id, last_modified, last_logged, registration_date FROM user_accounts WHERE username = ? AND password = ? LIMIT 1;';
			$stmt = $this->pdo->prepare($sql);
			$result = $stmt->execute([$username, $password]);
			if($stmt->rowCount() == 0) {
                echo "<script>console.log(\"No username/password found\")</script>";
				return null;
			}
			$user_row = $stmt->fetch();     // get user account's row
            $customer_id = $user_row['customer_id'];
            $business_id = is_null($user_row['business_id']) ? null : $user_row['business_id'];

			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);

			//  Find customer's entry associated with
			$sql = 'SELECT * from customers_info WHERE id = ? LIMIT 1';
			$stmt = $this->pdo->prepare($sql);
            $stmt->execute([$customer_id]);
			if($stmt->rowCount() == 0) {
				throw new Exception('The username does not have a customers_info entry associated');
			}
			$customer_row = $stmt->fetch();  // get customer's row

            //  Create customer obj
			$person = new customer(
				$customer_row[0],                                   // id
				$customer_row[1],                                   // name
				$customer_row[2],                                   // lastname
				new DateTime($customer_row[3]),                     // birthdate
				$customer_row[4],                                   // gender
				$this->get_customer_contacts($customer_id),         // phone numbers
				$this->get_customer_address($customer_id));         // gender

			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            var_dump($business_id);
            echo ($business_id);
            // Find business's entry if any associated with
			$company = null;
			if($business_id !== null) {
				$sql = 'SELECT * from businesses_info WHERE id = ? LIMIT 1';
				$stmt = $this->pdo->prepare($sql);
                $stmt->execute([$business_id]);
				if($stmt->rowCount() == 0) {
					throw new Exception('The username does not have a business_info entry associated, but the user profile states it');
				}
				$company_row = $stmt->fetch();  // get customer's row

                // Create business obj
				$company = new business(
					$business_id,                                   // id
					$person,                                        // owner
					$company_row['company_name'],                   // company_name
					$company_row['company_type'],                   // company_type
					$company_row['licence_number'],                 // licence_number
					$this->get_business_contacts($business_id),     // company's phone numbers
					$this->get_business_address($business_id),      // company's location address
					new DateTime($company_row['office_hour_start']),// office_hour_start
					new DateTime($company_row['office_hour_end']),  // office_hour_end
					$company_row['office_weekdays'],                // weekdays
					new DateTime($company_row['registration_date']),// registration date
                    $company_row['active']                          // business active
				);
			}
            do {
                $session_id = substr(md5(uniqid(mt_rand(), true)), 0, 20);
                $sql = 'SELECT id FROM active_session WHERE id = ? LIMIT 1';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$session_id]);
            }
            while($stmt->rowCount() > 0);
            $sql = 'INSERT INTO active_session (id, username) VALUE (?, ?)';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$session_id, $username]);

			return new user(
                $session_id,
				$user_row['id'],                                    // id
				$user_row['username'],                              // username
				$person,                                            // customer obj
				$company,                                           // company obj
				new DateTime($user_row['last_modified']),           // last modified
				new DateTime($user_row['last_logged']),             // last logged in
				new DateTime($user_row['registration_date'])        // registration date
			);
		}

        /**
         * Delete active sessions and user token from the database
         * @param string $username
         * @return void
         */
        function logout(string $username) : void {
            $sql = 'DELETE FROM active_session WHERE username = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$username]);
        }

        /**
         * Begin transaction
         * @return void
         */
        function begin() : void {
            $this->pdo->beginTransaction();
        }

        /**
         * Commit transaction
         * @return void
         */
        function commit() : void {
            $this->pdo->commit();
        }


        /**
         * Create a new user account
         * @param user $user
         * @param string $password
         * @return bool
         */
        function create_user(user $user, string $password) : bool {
			// Username is unique, check names in the form before creating the object

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
			return true;
		}

        /**
         * Register customer details: First Name, Last Name, Birthdate, Gender
         * Does not contain SQL transaction function calls
         * @param customer $c
         * @return bool
         */
        function register_customer(customer $c) : bool {
            // Contacts and Addresses have foreign keys dependent on Customer table
			// Register customer
			// Register phone numbers
			// Register addresses

			$sql = 'INSERT INTO customers_info (id, name, lastname, birthdate, gender) VALUES (?, ?, ?, ?, ?)';
			$stmt = $this->pdo->prepare($sql);
			$res = $stmt->execute([
				$c->id,
				$c->name,
				$c->lastname,
				$c->birthdate->format("Y-m-d"),
				$c->gender[0]
			]);
			if(!$res) return false;

			$sql = 'INSERT INTO customers_contacts (customer_id, phone) VALUES (?, ?);';
			$stmt = $this->pdo->prepare($sql);
			foreach($c->contacts->phones as $x) {
                if(empty($x)) continue;
				// stmt->execute() returns back a bool
				$res = $stmt->execute([
				  $c->id,
				  $x
				]);
			}
			if(!$res) return false;

			$addr = $c->address;
			$sql = 'INSERT INTO customers_addresses (customer_id, country_code, city_id, district, zipcode, street, holding, notes) VALUES (?, ?, ?, ?, ? ,?, ?, ?)';
			$stmt = $this->pdo->prepare($sql);
			$res = $stmt->execute([
				$c->id,
				$addr->country_code,
                $addr->city_id,
				$addr->district,
                $addr->zipCode,
				$addr->street,
				$addr->holding,
				$addr->notes
			]);
			if(!$res) return false;
			return true;
		}

        /**
         *  Register customer details: Company Name, Company Type, Licence Number, etc...
         *  Does not contain SQL transaction function calls
         * @param business $b
         * @return bool
         */
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
			$sql = 'INSERT INTO businesses_addresses (business_id, country_code, city_id, district, zipcode, street, holding, notes) VALUES (?, ?, ?, ?, ? ,?, ?, ?)';
			$stmt = $this->pdo->prepare($sql);
			$res = $stmt->execute([
				$b->id,
				$addr->country_code,
				$addr->city_id,
				$addr->district,
                $addr->zipCode,
				$addr->street,
				$addr->holding,
				$addr->notes
			]);

			if(!$res) return false;
			return true;
		}

        /**
         * Check whether the username is available on the server
         * @param string $username
         * @return bool
         */
        function is_username_available(string $username) : bool {
            if(empty($username) || $username === ' ') return false;
            $sql = "SELECT username FROM user_accounts WHERE username = \"{$username}\" LIMIT 1";
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
            $stmt = $this->pdo->query($sql);
            if($stmt && $stmt->rowCount() > 0) {
                return false;
            }
            else {
                return true;
            }
        }

        /**
         * Return an address object associated with the customer_id
         * @param string $customer_id
         * @return address|null
         */
        function get_customer_address(string $customer_id) : ?address {
			$sql = "SELECT * FROM customers_addresses WHERE customer_id = \"{$customer_id}\" LIMIT 1;";
			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
			$stmt = $this->pdo->query($sql);
			if($stmt) {
				$row = $stmt->fetch();
                // first param of address is customer id, no need to load address id
				return new address($row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
			}
			return false;
		}

        /**
         *  Return an address object associated with the business_id
         * @param string $business_id
         * @return address|null
         */
        function get_business_address(string $business_id) : ?address {
			$sql = "SELECT * FROM businesses_addresses WHERE business_id = \"{$business_id}\" LIMIT 1;";
			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
			$stmt = $this->pdo->query($sql);
			if($stmt) {
				$row = $stmt->fetch();
                // first param of address is customer id, no need to load address id
                return new address($row[1], $row[2], $row[3], $row[4], $row[5], $row[6], $row[7], $row[8]);
			}
			return false;
		}

        /**
         *  Return an contacts object associated with the customer_id
         * @param string $customer_id
         * @return contacts|null
         */
        function get_customer_contacts(string $customer_id) : ?contacts {
			$sql = "SELECT * FROM customers_contacts WHERE customer_id = \"{$customer_id}\"";
			$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
			$stmt = $this->pdo->query($sql);
			if($stmt) {
				$contacts = new contacts($customer_id, []);
				while($row = $stmt->fetch()) {
                    if(!empty($row[2])) $contacts->phones[] = $row[2];
				}
				return $contacts;
			}
			return null;
		}

        /**
         *   Returns a 'contacts' object associated with the business_id
         * @param string $business_id
         * @return contacts|null
         */
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
			return null;
		}

        /**
         * Change customer account to business user by updating the user account info
         * The business account must be already registered. user parameter comprehend new
         * business class' object
         * @param user $user
         * @return bool
         */
        function addBusinessToUser(user $user) : bool {
            $sql = 'UPDATE TABLE user_accounts SET business_id = ? WHERE id = ?;';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$user->business->id, $user->id]);
        }

        /**
         * If password is set updates the user account password,
         * otherwise update relative information
         * @param user $user
         * @param string|null $password
         * @return bool
         */
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

        /**
         * Update customer details such as Name, Last Name, etc...
         * Usage: Pass by reference a clone of the customer obj, in case of
         * success update current object's details
         * @param customer $c
         * @return bool
         */
        function update_customer_info(customer $c) : bool {
			$sql = 'UPDATE customers_info SET name = ?, lastname = ?, birthdate = ?, gender = ? WHERE id = ?;';
			$stmt = $this->pdo->prepare($sql);
			return $stmt->execute([$c->name, $c->lastname, $c->birthdate->format("Y-m-d"), $c->birthdate, $c->gender, $c->id]);
		}

		// Clone customer and pass it by reference, once info updated assign it to the main object

        /**
         * Update customer details such as Company Name, Company Type, etc...
         * Usage: Pass by reference a clone of the business obj, in case of
         * success update current object's details
         * @param business $b
         * @return bool
         */
        function update_business_info(business $b) : bool {
			$sql = 'UPDATE businesses_info SET company_name = ?, company_type = ?, licence_number = ?, office_hour_start = ?, office_hour_end = ?, office_weekdays = ? WHERE id = ?;';
			$stmt = $this->pdo->prepare($sql);
			return $stmt->execute([$b->company_name, $b->company_type, $b->licence_number, $b->start.format('H:i:s'), $b->end.format('H:i:s'), $b->weekdays, $b->id]);
		}

        /**
         * Update home address associated with the customer id from the address $a object
         * Usage: Pass by reference a clone, in case of success update current object's details
         * @param string $customer_id
         * @param address $a
         * @return bool
         */
        function update_customer_address(string $customer_id, address $a) : bool {
			$sql = 'UPDATE customers_addresses SET country_code = ?, city_id = ?, district = ?, street = ?, holding = ?, notes = ? WHERE customer_id = ?;';
			$stmt = $this->pdo->prepare($sql);
			return $stmt->execute([$a->country_code, $a->city_id, $a->district, $a->street, $a->holding, $a->notes, $customer_id]);
		}

		// Clone customer and pass it by reference, once info updated assign it to the main object

        /**
         * Update home address associated with the business id from the address $a object
         * Usage: Pass by reference a clone, in case of success update current object's details
         * @param string $business_id
         * @param address $a
         * @return bool
         */
        function update_business_address(string $business_id, address $a) : bool {
			$sql = 'UPDATE businesses_addresses SET country_code = ?, city_id = ?, district = ?, street = ?, holding = ?, notes = ? WHERE business_id = ?;';
			$stmt = $this->pdo->prepare($sql);
			return $stmt->execute([$a->country_code, $a->city_id, $a->district, $a->street, $a->holding, $a->notes, $business_id]);
		}

        /**
         * Add new Phone number for the given customer id
         * @param string $customer_id
         * @param string $phone
         * @return bool
         */
        function add_customer_contact(string $customer_id, string $phone) : bool {
			$sql = 'INSERT INTO customers_contacts (customer_id, phone) VALUES (?, ?);';
			$stmt = $this->pdo->prepare($sql);
			return $stmt->execute([$customer_id, $phone]);
		}

        /**
         * Add new Phone number for the given business id
         * @param string $business_id
         * @param string $phone
         * @return bool
         */
        function add_business_contact(string $business_id, string $phone) : bool {
			$sql = 'INSERT INTO businesses_contacts (business_id, phone) VALUES (?, ?);';
			$stmt = $this->pdo->prepare($sql);
			return $stmt->execute([$business_id, $phone]);
		}

        /**
         * Remove customer's phone number of the given id
         * @param string $customer_id
         * @param string $phone
         * @return bool
         */
        function remove_customer_contact(string $customer_id, string $phone) : bool {
			$sql = 'DELETE FROM customers_contacts WHERE customer_id = ? AND phone = ?;';
			$stmt = $this->pdo->prepare($sql);
			return $stmt->execute([$customer_id, $phone]);
		}

        /**
         * Remove business's phone number of the given id
         * @param string $business_id
         * @param string $phone
         * @return bool
         */
        function remove_business_contact(string $business_id, string $phone) : bool {
			$sql = 'DELETE FROM businesses_contacts WHERE business_id = ? AND phone = ?;';
			$stmt = $this->pdo->prepare($sql);
			return $stmt->execute([$business_id, $phone]);
		}

        /**
         * Generate an unique ID by checking the database
         * @return string|null
         */
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
                $unique_id = substr(md5(uniqid(mt_rand(), true)), 0, 12);       // assign a new random id
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$unique_id]);
                $exists = $stmt->rowCount();
            }
            return $unique_id;
        }

        function getCountries() : ?array {
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $sql = 'SELECT code, name FROM countries ORDER BY name ASC';
            $stmt = $this->pdo->query($sql);
            if(!$stmt) return null;

            return $stmt->fetchAll();
        }

        function getCities(string $countryCode) : ?array {
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $sql = "SELECT id, name FROM cities WHERE country_code = ? ORDER BY name ASC";
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$countryCode]);
            if(!$res || $stmt->rowCount() == 0) return null;

            return $stmt->fetchAll();
        }

		// destructor
		function __destruct() {
			$this->pdo = null;
		}

		// member variables
		private ?PDO $pdo = null;
	}