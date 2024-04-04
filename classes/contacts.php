<?php

    namespace classes;

    class contacts {
        public int $person_id;
        public array $phones = [];
        public bool $business_account = false;

        /**
         * @param int $person_id
         * @param array $phones
         * @param bool $business_account
         */
        public function __construct(int $person_id, array $phones, bool $business_account) {
            $this->person_id = $person_id;
            $this->phones = $phones;
            $this->business_account = $business_account;
        }

    }
