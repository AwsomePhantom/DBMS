<?php

    namespace classes;

    class customer {
        public ?int $id;
        public string $name, $lastname, $birthdate, $gender;
        public contacts $contacts;
        public address $address;

        /**
         * @param int $id
         * @param string $name
         * @param string $lastname
         * @param string $birthdate
         * @param string $gender
         * @param contacts $contacts
         * @param address $address
         */
        public function __construct(?int $id, string $name, string $lastname, string $birthdate, string $gender, contacts $contacts, address $address) {
            $this->id = $id;
            $this->name = $name;
            $this->lastname = $lastname;
            $this->birthdate = $birthdate;
            $this->gender = $gender;
            $this->contacts = $contacts;
            $this->address = $address;
        }
    }
