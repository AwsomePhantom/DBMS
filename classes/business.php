<?php

    namespace classes;
    class business {
        public int $id;
        public Person $person;
        public string $company_name, $company_type, $licence_number;
        public DateTime $start, $end, $registration_date;
        public contacts $contacts;
        public address $address;
        public string $weekdays;
        public bool $active;

        /**
         * @param int $id
         * @param Person $person
         * @param string $company_name
         * @param string $company_type
         * @param string $licence_number
         * @param DateTime $start
         * @param DateTime $end
         * @param DateTime $registration_date
         * @param contacts $contacts
         * @param address $address
         * @param string $weekdays
         * @param bool $active
         */
        public function __construct(int $id, Person $person, string $company_name, string $company_type, string $licence_number, DateTime $start, DateTime $end, DateTime $registration_date, contacts $contacts, address $address, string $weekdays, bool $active) {
            $this->id = $id;
            $this->person = $person;
            $this->company_name = $company_name;
            $this->company_type = $company_type;
            $this->licence_number = $licence_number;
            $this->start = $start;
            $this->end = $end;
            $this->registration_date = $registration_date;
            $this->contacts = $contacts;
            $this->address = $address;
            $this->weekdays = $weekdays;
            $this->active = $active;
        }
    }

