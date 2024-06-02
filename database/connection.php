<?php
    $GLOBALS['CONNECTION_VARS'] = true;
	require_once ROOT_DIR . '/classes/contacts.php';
	require_once ROOT_DIR . '/classes/address.php';
	require_once ROOT_DIR . '/classes/customer.php';
	require_once ROOT_DIR . '/classes/business.php';
	require_once ROOT_DIR . '/classes/user.php';

use classes\address;
use classes\business;
use classes\contacts;
use classes\customer;
use classes\user;

require_once (ROOT_DIR . '/database/variables.php');

	const CONNECTION = new connection();
	CONNECTION->connect();


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
				$this->pdo = new PDO($str, CONN_INFO['USERNAME'], CONN_INFO['PASSWORD'], $opts);
				// connected
			}
			catch (PDOException $e) {
				throw new PDOException($e->getMessage(), $e->getCode());
			}
		}

        /**
         * Returns the PDO object
         * @return PDO
         */
        function getPDOObject() : PDO {
            return $this->pdo;
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
			$sql = 'SELECT id, username, email, customer_id, business_id, last_modified, last_logged, registration_date FROM user_accounts WHERE username = ? AND password = ? LIMIT 1;';
			$stmt = $this->pdo->prepare($sql);
			$result = $stmt->execute([$username, $password]);
			if(!$result || $stmt->rowCount() == 0) {
                //echo "<script>console.log(\"No username/password found\")</script>";
				return null;
			}
			$user_row = $stmt->fetch();     // get user account's row
            $stmt->closeCursor();
            $sql = 'DELETE FROM active_session WHERE username = ?';
            $customer_id = $user_row['customer_id'];
            $stmt_delete_old_sessions = $this->pdo->prepare($sql);
            $stmt_delete_old_sessions->execute([$username]);
            $stmt_delete_old_sessions->closeCursor();
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
                    $company_row['active'],                         // business active
					new DateTime($company_row['registration_date']) // registration date
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
                $user_row['email'],                                 // email
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
         * @return bool
         */
        function logout(string $username) : bool {
            $sql = 'DELETE FROM active_session WHERE username = ?';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$username]);
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
         * Rollback the database to its previous state since beginTransaction
         * @return void
         */
        function rollback() : void {
            $this->pdo->rollBack();
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

				$sql = 'INSERT INTO user_accounts (id, username, password, email, customer_id, business_id) VALUES (?, ?, ?, ?, ?, ?)';
				$stmt = $this->pdo->prepare($sql);
				$res = $stmt->execute([
					$user->id,
					$user->username,
					$password,
                    $user->email,
					$user->customer->id,
					$user->business->id
				]);
			}
			else {
				$res = $this->register_customer($user->customer);   // insert customer details into the db
				if(!$res) return false;

				$sql = 'INSERT INTO user_accounts (id, username, email, password, customer_id) VALUES (?, ?, ?, ?, ?)';
				$stmt = $this->pdo->prepare($sql);
				$res = $stmt->execute([
					$user->id,
					$user->username,
                    $user->email,
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
			$sql = 'INSERT INTO businesses_info(id, owner_id, company_name, company_type, licence_number, office_hour_start, office_hour_end, office_weekdays, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';
			$stmt = $this->pdo->prepare($sql);
			$res = $stmt->execute([
				$b->id,
				$b->customer->id,
				$b->company_name,
				$b->company_type,
				$b->licence_number,
				$b->start->format('H:i:s'),
				$b->end->format('H:i:s'),
				$b->weekdays,
                (int)$b->active
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
			return null;
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
			return null;
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
            $sql = 'UPDATE user_accounts SET business_id = ? WHERE id = ?;';
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
			if($password !== null) {     // update only password
				$sql = 'UPDATE user_accounts SET password = ?, last_logged = NOW() WHERE id = ?';
				$stmt = $this->pdo->prepare($sql);
				$result = $stmt->execute([$password, $user->id]);
			}
			else {      // for now, update only the username
				$sql = 'UPDATE user_accounts SET username = ?, last_modified = NOW() WHERE id = ?';
				$stmt = $this->pdo->prepare($sql);
				$result = $stmt->execute([$user->username, $user->id]);
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
			return $stmt->execute([$b->company_name, $b->company_type, $b->licence_number, $b->start->format('H:i:s'), $b->end->format('H:i:s'), $b->weekdays, $b->id]);
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
         * Generate a unique ID by checking the database
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

        /**
         * Returns the list of all counties in the database
         * @return array|null
         */
        function getCountries() : ?array {
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $sql = 'SELECT code, name FROM countries ORDER BY name ASC';
            $stmt = $this->pdo->query($sql);
            if(!$stmt) return null;

            return $stmt->fetchAll();
        }

        /**
         * Returns a list of cities associated with a country code
         * @param string $countryCode
         * @return array|null
         */
        function getCities(string $countryCode) : ?array {
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $sql = "SELECT id, name FROM cities WHERE country_code = ? ORDER BY name ASC";
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$countryCode]);
            if(!$res || $stmt->rowCount() == 0) return null;

            return $stmt->fetchAll();
        }

        /**
         * Return the name of the city from the city_id
         * @param int $city_id
         * @return string|null
         */
        function getCityName(int $city_id) : ?string {
            $sql = 'SELECT name FROM cities WHERE id = ? LIMIT 1';
            $stmt = $this->pdo->prepare($sql);
            if(!$stmt->execute([$city_id])) return null;
            $arr = $stmt->fetch();
            return reset($arr);
        }

        function getCountryCode(string $countryName) : ?string {
            $sql = 'SELECT code FROM countries WHERE name = ? LIMIT 1';
            $stmt = $this->pdo->prepare($sql);
            if(!$stmt->execute([$countryName])) return null;
            return $stmt->fetch()[0];
        }


        /**
         * Return the name of the contries associated with the 3 letter code
         * @param string $country_code
         * @return string|null
         */
        function getCountryName(string $country_code) : ?string {
            if(strlen($country_code) > 3) return null;
            $sql = 'SELECT name FROM countries WHERE countries.code = ? LIMIT 1';
            $stmt = $this->pdo->prepare($sql);
            if(!$stmt->execute([$country_code])) return null;
            return $stmt->fetch()[0];
        }

        /**
         * Returns all the social posts published by users
         * Country code [optional] is a three-letter code
         * @param string|null $country_code
         * @param int|null $limit
         * @return array|null
         */
        function getAllPosts(?string $country_code, ?int $limit) : ?array {
            $sql = 'SELECT A.id as post_id, A.user_id AS user_id, A.post_title AS title, A.post_content AS content, A.address AS address, B.name AS city, B.country_code AS country_code, A.date_time AS date FROM post_issues AS A JOIN cities AS B ON A.city_id = B.id';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            if($country_code) {
                $sql .=  " WHERE B.country_code = \'" . $country_code .  "\'";
            }
            $sql .= " ORDER BY A.date_time DESC";
            if($limit) {
                $sql .= " LIMIT " . $limit;
            }
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute();
            if(!$res) {
                return null;
            }
            $arr = $stmt->fetchAll();
            $stmt->closeCursor();
            foreach ($arr as $key => $row) {
                $author = $this->getUserNameTitle($row['user_id']);
                $arr[$key]['author'] = $author;
            }
            return $arr;
        }

        /**
         * Returns a single user post
         * Used for social post replies
         * @param int $post_id
         * @return array|null
         */
        function getSinglePost(int $post_id) : ?array {
            $sql = 'SELECT A.id as post_id, A.user_id AS user_id, A.post_title AS title, A.post_content AS content, A.address AS address, B.name AS city, B.country_code AS country_code, A.date_time AS date FROM post_issues AS A JOIN cities AS B ON A.city_id = B.id WHERE A.id = ? LIMIT 1';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$post_id]);
            if(!$res) return null;
            $arr = $stmt->fetchAll();
            $stmt->closeCursor();
            foreach ($arr as $key => $row) {
                $author = $this->getUserNameTitle($row['user_id']);
                $arr[$key]['author'] = $author;
            }
            return reset($arr);
        }

        /**
         * Returns all the posts shared by a single user
         * Used in the profile page
         * @param string $user_id
         * @return array|null
         */
        function getSingleUserPosts(string $user_id) : ?array {
            $sql = 'SELECT A.id as post_id, A.user_id AS user_id, A.post_title AS title, A.post_content AS content, A.address AS address, B.name AS city, B.country_code AS country_code, A.date_time AS date FROM post_issues AS A JOIN cities AS B ON A.city_id = B.id WHERE A.user_id = ? ORDER BY A.date_time DESC';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$user_id]);
            if(empty($res) || $stmt->rowCount() <= 0) return null;
            $arr = $stmt->fetchAll();
            $stmt->closeCursor();
            foreach ($arr as $key => $row) {
                $author = $this->getUserNameTitle($row['user_id']);
                $arr[$key]['author'] = $author;
            }
            return $arr;
        }

        /**
         * Returns the posts shared by a single user
         * Used for user profile
         * @param string $user_id
         * @param int|null $limit
         * @return array|null
         */
        function getUserPosts(string $user_id, ?int $limit) : ?array {
            $sql = 'SELECT id, user_id, post_title, post_content, city_id, address, date_time from post_issues WHERE user_id = ? ORDER BY date_time DESC';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            if($limit) {
                $sql .=  " LIMIT " . $limit;
            }
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$user_id]);
            return $stmt->fetchAll();
        }

        /**
         * Returns the name and last name in a single string
         * of a user_id, in case the user is associated with a business
         * returns the company name. Used to find social post author's name
         * @param string $user_id
         * @return string|null
         */
        function getUserNameTitle(string $user_id) : ?string {
            $sql = 'SELECT customer_id, business_id FROM user_accounts WHERE id = ? LIMIT 1';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$user_id]);
            $row = $stmt->fetch();
            if(!$row) return null;
            $stmt->closeCursor();
            if(is_null($row[1])) {
                $sql = 'SELECT name, lastname FROM customers_info WHERE id = ? LIMIT 1';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$row[0]]);
                $res = $stmt->fetch();
                if(!$res) return null;
                return $res[0] . " " . $res[1];
            }
            else {
                $sql = 'SELECT company_name FROM businesses_info WHERE id = ? LIMIT 1';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$row[1]]);
                $arr = $stmt->fetch();
                return reset($arr);
            }
        }

        /**
         * Returns all the replies to a post
         * @param int $post_id
         * @return array|null
         */
        function getPostReplies(int $post_id) : ?array{
            $sql = 'SELECT id, post_id, user_id, content, datetime AS date FROM post_answers WHERE post_id = ?';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            if(!$stmt->execute([$post_id])) {
                return null;
            }
            $arr = $stmt->fetchAll();
            $stmt->closeCursor();
            foreach ($arr as $key => $row) {
                $author = $this->getUserNameTitle($row['user_id']);
                $arr[$key]['author'] = $author;
            }
            return $arr;
        }

        /**
         * Add reply message to a social post
         * @param int $post_id
         * @param string $user_id
         * @param string $content
         * @return bool
         */
        function addPostReply(int $post_id, string $user_id, string $content) : bool
        {
            $sql = 'INSERT INTO post_answers (post_id, user_id, content) VALUES (?, ?, ?)';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$post_id, $user_id, $content]);
        }

        /**
         * Get the number of replies on post, returns null
         * in case none
         * @param int $post_id
         * @return int|null
         */
        function getPostRepliesCount(int $post_id) : ?int {
            $sql = 'SELECT COUNT(*) FROM post_answers WHERE post_id = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$post_id]);
            $row = $stmt->fetch(PDO::FETCH_NUM);
            if($row[0] === 0) return null;
            else return $row[0];
        }

        /**
         * Delete social post's message reply
         * @param int $reply_id
         * @param int $post_id
         * @param string $user_id
         * @return bool
         */
        function deletePostReply(int $reply_id, int $post_id, string $user_id) : bool {
            $sql = 'DELETE FROM post_answers WHERE id = ? AND post_id = ? AND user_id = ?';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$reply_id, $post_id, $user_id]);
        }

        function addNewPost(string $user_id, string $title, string $content, string $city_id, string $address) : bool {
            $sql = 'INSERT INTO post_issues (user_id, post_title, post_content, city_id, address) VALUES (?, ?, ?, ?, ?)';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$user_id, $title, $content, $city_id, $address]);
        }

        /**
         * Deletes a social post and all the replies associated with it
         * Accessible only from the author of the post
         * @param int $post_id
         * @param string $user_id
         * @return void
         */
        function deletePost(int $post_id, string $user_id) : void {
            $sql = 'DELETE FROM post_answers WHERE post_id = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$post_id]);
            $sql = 'DELETE FROM post_issues WHERE id = ? AND user_id = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$post_id, $user_id]);

        }

        /**
         * Search a post based on a keyword
         * @param string $keyword
         * @return array|null
         */
        function searchPosts(string $keyword) : ? array {
            $sql = "SELECT A.id as post_id, A.user_id AS user_id, A.post_title AS title, A.post_content AS content, A.address AS address, B.name AS city, B.country_code AS country_code, A.date_time AS date FROM post_issues AS A JOIN cities AS B ON A.city_id = B.id WHERE post_title LIKE '%" . htmlentities($keyword) . "%' OR post_content LIKE '%" . htmlentities($keyword) . "%' ORDER BY date DESC";

            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            if(!$stmt->execute()) {
                return null;
            }
            $arr = $stmt->fetchAll();
            $stmt->closeCursor();
            foreach ($arr as $key => $row) {
                $author = $this->getUserNameTitle($row['user_id']);
                $arr[$key]['author'] = $author;
            }
            return $arr;
        }

        function filterPosts(?int $city_id, ?string $dateTime) : ?array {
            $sql = "SELECT A.id as post_id, A.user_id AS user_id, A.post_title AS title, " .
                "A.post_content AS content, A.address AS address, B.name AS city, B.country_code AS country_code, " .
                "A.date_time AS date FROM post_issues AS A JOIN cities AS B ON A.city_id = B.id ";
            $temp_a = "WHERE A.city_id = " . $city_id . " ";
            $temp_b = "WHERE DATE(A.date_time) >= DATE(" . $dateTime . ") ";
            if(!is_null($city_id) && !is_null($dateTime)) $sql .= $temp_a . "AND " . $temp_b;
            else if(!is_null($city_id)) $sql .= $temp_a;
            else if(!is_null($dateTime)) $sql .= $temp_b;
            $sql .= "ORDER BY A.date_time DESC";

            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            if(!$stmt->execute()) {
                return null;
            }
            $arr = $stmt->fetchAll();
            $stmt->closeCursor();
            foreach ($arr as $key => $row) {
                $author = $this->getUserNameTitle($row['user_id']);
                $arr[$key]['author'] = $author;
            }
            return $arr;
        }

        /**
         * @param string $user_id
         * @param string $title
         * @param string $country_code
         * @param string $district
         * @param string $address
         * @param string $message
         * @param string $gps_x
         * @param string $gps_y
         * @return bool
         */
        function reportIncident(string $user_id, string $title, string $country_code, string $district, string $address, string $message, string $gps_x, string $gps_y) : bool {
            $sql = 'INSERT INTO incident_reports (user_id, GPSX, GPSY, country_code, district, street, message_title, message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
            $stmt = $this->pdo->prepare($sql);
            if(!$stmt->execute([$user_id, $gps_x, $gps_y, $country_code, $district, $address, $title, $message])) {
                return false;
            }
            return true;
        }

        /**
         * Get all the incident reports of a single user
         * @param string $user_id
         * @return array|null
         */
        function getIncidentReports(string $user_id) : ?array {
            $sql = 'SELECT id, user_id, GPSX AS gpsx, GPSY AS gpsy, country_code, district, street AS address, message_title AS title, message, datetime AS date FROM incident_reports WHERE user_id = ? ORDER BY date DESC';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$user_id]);
            if(empty($res) || $stmt->rowCount() <= 0) return null;
            $arr = $stmt->fetchAll();
            if(empty($arr)) return null;
            $stmt->closeCursor();
            foreach ($arr as $key => $row) {
                $author = $this->getUserNameTitle($row['user_id']);
                $arr[$key]['author'] = $author;
            }
            return $arr;
        }

        /**
         * Return the count of incidents report of a single user
         * @param string $user_id
         * @return int|null
         */
        function getUserIncidentsReportsCount(string $user_id) : ? int {
            $sql = 'SELECT COUNT(*) FROM incident_reports WHERE user_id = ?';
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$user_id]);
            if(empty($res) || $stmt->rowCount() <= 0) return null;
            return $stmt->fetchColumn();
        }

        /**
         * List all incidents reports for businesses
         * @return array|null
         */
        function getAllIncidentsReports() : ?array {
            $sql = 'SELECT id, user_id, GPSX AS gpsx, GPSY AS gpsy, country_code, district, street AS address, message_title AS title, message, datetime AS date FROM incident_reports ORDER BY date DESC';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute();
            if(empty($res) || $stmt->rowCount() <= 0) return null;
            $arr = $stmt->fetchAll();
            if(empty($arr)) return null;
            $stmt->closeCursor();
            foreach ($arr as $key => $row) {
                $author = $this->getUserNameTitle($row['user_id']);
                $arr[$key]['author'] = $author;
            }
            return $arr;
        }

        /**
         *  Return the count of incidents report of all users
         * @return int|null
         */
        function getAllIncidentsReportsCount() : ? int {
            $sql = 'SELECT COUNT(*) FROM incident_reports';
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute();
            if(empty($res) || $stmt->rowCount() <= 0) return null;
            $arr = $stmt->fetch();
            return reset($arr);
        }

        /**
         * Deletes an incident report
         * @param string $user_id
         * @param int $report_id
         * @return bool
         */
        function deleteIncidentReport(string $user_id, int $report_id) : bool {
            $sql = 'DELETE FROM incident_reports WHERE user_id = ? AND id = ?';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$user_id, $report_id]);
        }

        /**
         * Add a repair request for a business owner
         * @param int $incident_id
         * @param string $company_user_id
         * @return bool
         */
        function addRepairRequest(int $incident_id, string $company_user_id) : bool {
            $sql = 'INSERT INTO list_repair_requests (incident_id, user_id) VALUES (?, ?)';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$incident_id, $company_user_id]);
        }

        /**
         * Cancel a repair offer
         * @param int $incident_id
         * @param string $company_user_id
         * @return bool
         */
        function cancelRepairRequest(int $izncident_id, string $company_user_id) : bool {
            $sql = 'DELETE FROM list_repair_requests WHERE incident_id = ? AND user_id = ?';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$incident_id, $company_user_id]);
        }

        function isRepairRequested(int $incident_id, string $company_user_id) : bool {
            $sql = 'SELECT COUNT(*) FROM list_repair_requests WHERE incident_id = ? AND user_id = ? LIMIT 1';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$incident_id, $company_user_id]);
            $row = $stmt->fetch();
            $row = reset($row);
            return (bool)$row;
        }

        /**
         * List all the businesses that offered repair service
         * @param string $incident_id
         * @return array|null
         */
        function listRepairRequests(string $incident_id) : ?array {
            $sql = 'SELECT id, incident_id, user_id, datetime AS date from list_repair_requests WHERE incident_id = ? ORDER BY date DESC';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$incident_id]);
            $arr = $stmt->fetchAll();
            if(empty($arr)) return null;
            $stmt->closeCursor();
            foreach ($arr as $key => $row) {
                $sql = "SELECT A.id, A.company_name, A.company_type, B.email, C.street AS address, C.district AS city, C.country_code FROM businesses_info A JOIN user_accounts B JOIN businesses_addresses C ON A.id = B.business_id AND A.id = C.business_id WHERE B.id = '" . $row['user_id'] . "' LIMIT 1";
                $stmt = $this->pdo->prepare($sql);
                if(!$stmt->execute()) return null;
                $temp = $stmt->fetch();
                $arr[$key]['company_name'] = $temp['company_name'];
                $arr[$key]['company_type'] = $temp['company_type'];
                $arr[$key]['email'] = $temp['email'];
                $arr[$key]['address'] = $temp['address'];
                $arr[$key]['city'] = $temp['city'];
                $arr[$key]['country_code'] = $temp['country_code'];
            }
            return $arr;
        }

        /**
         * Accept repair service from a business
         * @param string $request_id
         * @param string $customer_id
         * @param string $company_name
         * @return bool
         */
        function acceptRepairRequest(string $request_id, string $user_id, string $customer_id, string $company_name, string $rescue_address) : bool {
            $this->pdo->beginTransaction();
            $sql = 'SELECT id FROM businesses_info WHERE company_name = ? LIMIT 1';
            $stmt = $this->pdo->prepare($sql);
            if(!$stmt->execute([$company_name])) return false;
            $business_id = $stmt->fetchColumn();
            $stmt->closeCursor();
            $sql = 'INSERT INTO financial_statements (customer_id, business_id, location_addr, advance_payment, total_payment, discount) VALUES (?, ?, ?, 0, 0, 0)';
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$customer_id, $business_id, $rescue_address]);
            if(!$res) return false;
            $statement_id = $this->pdo->lastInsertId();
            $sql = "INSERT INTO financial_accounts (statement_id, date, customer_id, business_id, vehicle_service_desc, sub_total) VALUES (?, NOW(), ?, ?, 'START', 0)";
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$statement_id, $customer_id, $business_id]);
            if(!$res) return false;
            $stmt->closeCursor();
            $sql = 'DELETE FROM list_repair_requests WHERE incident_id = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$request_id]);
            $stmt->closeCursor();
            $sql = 'DELETE FROM incident_reports WHERE id = ? AND user_id = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$request_id, $user_id]);
            $this->pdo->commit();
            return true;
        }

        /**
         * Returns the count of the servicing opened of a single customer
         * @param string $customer_id
         * @return int
         */
        function getUserInvoicesCount(string $customer_id) : int {
            $sql = 'SELECT DISTINCT COUNT(statement_id) FROM financial_accounts WHERE customer_id = ?';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$customer_id]);
            return $stmt->fetchColumn();
        }

        /**
         * Returns the count of all servicing opened
         * @return int
         */
        function getTotalInvoicesCount() : int {
            $sql = 'SELECT DISTINCT COUNT(statement_id) FROM financial_accounts';
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute();
            return $stmt->fetchColumn();
        }

        function getUserInvoices(string $customer_id) : ?array {
            $sql = 'SELECT id as statement_id, start_date AS start, end_date AS end, issue_date AS issued, location_addr as rescue_address, advance_payment AS advance, total_payment AS payment, discount, total_expense AS expenses, net_product AS net, gross_income AS gross, status FROM financial_statements WHERE customer_id = ? ORDER BY start DESC';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$customer_id]);
            $statements = $stmt->fetchAll();
            if(empty($statements)) return null;
            $stmt->closeCursor();
            foreach ($statements as $key => $statement) {
                $sql = 'SELECT id, date, customer_id, business_id, parts_expense, vehicle_parts_desc, vehicle_service_desc, service_revenue, notes, sub_total FROM financial_accounts WHERE statement_id = ? ORDER BY date';
                $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([$statement['statement_id']]);
                $accounts = $stmt->fetchAll();
                if(empty($accounts)) throw new Exception("Financial accounts not found, but Financial statement present");
                $statements[$key]['accounts'] = reset($accounts);
            }
            return $statements;
        }

        /**
         * Returns the numbers of financial statements opened with some basic data
         * Returns array with attributes id, date and name
         * @param string $business_id
         * @return array|null
         */
        function getBusinessInvoicesCount(string $business_id) : ?array {
            $sql = 'SELECT A.id AS statement_id, DATE(A.start_date) AS date, CONCAT(B.name, " ", B.lastname) AS name, A.status AS status FROM financial_statements A JOIN customers_info B ON A.customer_id = B.id WHERE business_id = ? ORDER BY date DESC';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$business_id]);
            return $stmt->fetchAll();
        }

        function getBusinessInvoices(string $business_id, int $index) : ?array {
            $sql = 'SELECT id as statement_id, start_date AS start, end_date AS end, issue_date AS issued, location_addr as rescue_address, advance_payment AS advance, total_payment AS payment, discount, total_expense AS expenses, net_product AS net, gross_income AS gross, status FROM financial_statements WHERE business_id = ? AND id = ? LIMIT 1';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$business_id, $index]);
            $statements = $stmt->fetchAll();
            $statements = reset($statements);
            if(empty($statements)) return null;
            return $statements;
        }

        /**
         * Add Financial account with works done
         * Input param assoc array([customer_id], [business_id], [parts_expense], [vehicle_parts_desc], [vehicle_service_desc], [service_revenue], [notes], [sub_total], [tax])
         * @param array $desc
         * @return bool
         */
        function addFinancialAccount(array $desc) : bool {
            $sql = 'INSERT INTO financial_accounts(statement_id, date, customer_id, business_id, parts_expense, vehicle_parts_desc, vehicle_service_desc, service_revenue, notes, sub_total) VALUES(?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';
            $this->pdo->beginTransaction();
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$desc['statement_id'], $desc['customer_id'], $desc['business_id'], $desc['parts_expense'], $desc['vehicle_parts_desc'], $desc['vehicle_service_desc'], $desc['service_revenue'], $desc['notes'], $desc['sub_total']]);
            if(!$res) return $res;
            $sql = 'UPDATE financial_statements SET total_expense = total_expense + ?, net_product = net_product + ?, gross_income = net_product * ? WHERE id = ?';
            $stmt = $this->pdo->prepare($sql);
            $res = $stmt->execute([$desc['parts_expense'], $desc['sub_total'], $desc['sub_total'] * $desc['tax'], $desc['statement_id']]);
            if($res) {
                $this->pdo->commit();
            }
            return $res;

        }

        /**
         * Returns an array with the lists of works done
         * @param $business_id
         * @param $statement_id
         * @return array|null
         */
        function getFinancialAccounts($business_id, $statement_id) : ?array {
            $sql = 'SELECT * FROM financial_accounts WHERE business_id = ? AND statement_id = ? ORDER BY date';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$business_id, $statement_id]);
            $accounts = $stmt->fetchAll();
            if(!empty($accounts)) return $accounts;
            return null;
        }

        function getTheme(string $user_id) : ?string {
            $sql = 'SELECT theme FROM user_accounts WHERE id = ? LIMIT 1';
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_NUM);
            $stmt = $this->pdo->prepare($sql);
            if(!$stmt->execute([$user_id])) {
                return null;
            }
            $row = $stmt->fetch();
            if(is_null($row)) return null;
            return $row;
        }

        function setTheme(string $user_id, string $theme) : bool {
            $sql = 'UPDATE user_accounts SET theme = ? WHERE id = ?';
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$theme, $user_id]);
        }

        /**
         * Destructor
         */
		function __destruct() {
			$this->pdo = null;
		}

		// member variables
		private ?PDO $pdo = null;
	}