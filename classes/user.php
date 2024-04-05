<?php

    namespace classes;
    use DateTime;

    // separate functions for user class passing pdo as parameter
    class user {
        public string $id;
        public string $username;
        public customer $customer;
        public ?business $business;
        public ?DateTime $modified, $logged, $registered;

        /**
         * @param string $id
         * @param string $username
         * @param customer $customer
         * @param business|null $business
         * @param DateTime|null $modified
         * @param DateTime|null $logged
         * @param DateTime|null $registered
         */
        public function __construct(string $id, string $username, customer $customer, ?business $business, ?DateTime $modified, ?DateTime $logged, ?DateTime $registered) {
            $this->id = $id;
            $this->username = $username;
            $this->customer = $customer;
            $this->business = $business;
            $this->modified = $modified;
            $this->logged = $logged;
            $this->registered = $registered;
        }

    }
