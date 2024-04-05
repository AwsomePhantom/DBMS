<?php

    namespace classes;

    // In PHP classes for both customers and businesses are the same,
    // in SQL they have separate tables
    class address {
        public ?int $id;
        public int $customer_id;
        public string $country_code, $city;
        public string $district, $street, $holding;
        public string $notes;

        /**
         * @param int $id
         * @param int $customer_id
         * @param string $country_code
         * @param string $city
         * @param string $district
         * @param string $street
         * @param string $holding
         * @param string $notes
         */
        public function __construct(?int $id, int $customer_id, string $country_code, string $city, string $district, string $street, string $holding, string $notes) {
            $this->id = $id;
            $this->customer_id = $customer_id;
            $this->country_code = $country_code;
            $this->city = $city;
            $this->district = $district;
            $this->street = $street;
            $this->holding = $holding;
            $this->notes = $notes;
        }


    }