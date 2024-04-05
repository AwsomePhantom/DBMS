<?php

    namespace classes;
    use DateTime;

    class business {
        public string $id;     // At the moment of creation pass null, it will be created inside the register function
        public customer $customer;
        public string $company_name, $company_type, $licence_number;
        public DateTime $start, $end;
        public ?DateTime $registration_date;
        public contacts $contacts;
        public address $address;
        public string $weekdays;
        public bool $active;

        /**
         * @param string $id
         * @param customer $customer
         * @param string $company_name
         * @param string $company_type
         * @param string $licence_number
         * @param DateTime $start
         * @param DateTime $end
         * @param DateTime|null $registration_date
         * @param contacts $contacts
         * @param address $address
         * @param string $weekdays
         * @param bool $active
         */
        public function __construct(string $id, customer $customer, string $company_name, string $company_type, string $licence_number, contacts $contacts, address $address, DateTime $start, DateTime $end, string $weekdays, ?DateTime $registration_date, bool $active) {
            $this->id = $id;
            $this->customer = $customer;
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

