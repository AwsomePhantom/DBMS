<?php

    namespace classes;

    // In PHP classes for both customers and businesses are the same,
    // in SQL they have separate tables
    class contacts {
        public ?int $id;
        public array $phones = [];

        /**
         * @param ?int $id
         * @param array $phones
         */
        public function __construct(?int $id, array $phones) {
            $this->id = $id;
            $this->phones = $phones;
        }

    }
