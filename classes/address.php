<?php

    namespace classes;

    // In PHP classes for both customers and businesses are the same,
    // in SQL they have separate tables
    class address {
        public string $owner_id;
        public string $country_code;
        public int $city_id;
        public string $district, $zipCode, $street, $holding;
        public string $notes;

        /**
         * @param string $owner_id
         * @param string $country_code
         * @param int $city_id
         * @param string $district/state
         * @param string $zipCode
         * @param string $street/address
         * @param string $holding
         * @param string $notes
         */
        public function __construct(string $owner_id, string $country_code, int $city_id, string $district, string $zipCode, string $street, string $holding, string $notes) {
            $this->owner_id = $owner_id;
            $this->country_code = substr($country_code, 0, 3);
            $this->city_id = (int)$city_id;
            $this->district = $district;
            $this->zipCode = $zipCode;
            $this->street = $street;
            $this->holding = $holding;
            $this->notes = $notes;
        }
    }
