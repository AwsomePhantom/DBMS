<?php

    namespace classes;

    // In PHP classes for both customers and businesses are the same,
    // in SQL they have separate tables
    class contacts {
        public ?string $owner_id;
        public array $phones = [];

        /**
         * @param string|null $owner_id
         * @param array $phones
         */
        public function __construct(?string $owner_id, array $phones) {
            $this->$owner_id = $owner_id;
            $this->phones = $phones;
        }

    }
