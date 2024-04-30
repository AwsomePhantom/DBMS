<?php

    namespace classes;
    use DateTime;

    // separate functions for user class passing pdo as parameter
    class user {
        public ?string $session_id = null;
        public string $id;
        public string $username;
        public customer $customer;
        public ?business $business;
        public ?DateTime $modified, $logged, $registered;

        /**
         * @param string|null $session_id
         * @param string $id
         * @param string $username
         * @param customer $customer
         * @param business|null $business
         * @param DateTime|null $modified
         * @param DateTime|null $logged
         * @param DateTime|null $registered
         */
        public function __construct(?string $session_id, string $id, string $username, customer $customer, ?business $business, ?DateTime $modified, ?DateTime $logged, ?DateTime $registered) {
            $this->session_id = $session_id;
            $this->id = $id;
            $this->username = $username;
            $this->customer = $customer;
            $this->business = $business;
            $this->modified = $modified;
            $this->logged = $logged;
            $this->registered = $registered;
        }

        function __toString() : string {
            $out = "<table class='table'><thead><th><td>ID</td><td>Username</td><td>Name</td><td>Account Type</td></th></thead>" .
            "<tbody><tr><td>{$this->id}</td><td>{$this->username}</td><td>{$this->customer->name} {$this->customer->lastname}</td><td>". (($this->business !== null) ? "business" : "customer") . "</td></tr></tbody></table>";
            return $out;
        }

    }
