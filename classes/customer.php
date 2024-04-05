<?php

    namespace classes;
    use DateTime;

    class customer {
        public string $id;    // At the moment of creation pass null, it will be created inside the register function
        public string $name, $lastname, $gender;
        public DateTime $birthdate;
        public contacts $contacts;
        public address $address;

        /**
         * @param string $id
         * @param string $name
         * @param string $lastname
         * @param DateTime $birthdate
         * @param string $gender
         * @param contacts $contacts
         * @param address $address
         */
        public function __construct(string $id, string $name, string $lastname, DateTime $birthdate, string $gender, contacts $contacts, address $address) {
            $this->id = $id;
            $this->name = $name;
            $this->lastname = $lastname;
            $this->birthdate = $birthdate;
            $this->gender = $gender;
            $this->contacts = $contacts;
            $this->address = $address;
        }
    }
