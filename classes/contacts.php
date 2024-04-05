<?php

    namespace classes;

    // In PHP classes for both customers and businesses are the same,
    // in SQL they have separate tables
    class contacts {
        public ?int $id;
        public int $customer_id;
        public array $phones = [];

        /**
         * @param int $customer_id
         * @param array $phones
         * @param bool $business_account
         */
        public function __construct(?int $id, int $customer_id, array $phones) {
            $this->id = $id;
            $this->customer_id = $customer_id;
            $this->phones = $phones;
        }

    }
