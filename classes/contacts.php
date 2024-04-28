<?php

    namespace classes;

    class contacts {
        public int $id;
        public array $phones = [];

        /**
         * @param int $id
         * @param array $phones
         */
        public function __construct(int $id, String ...$phones) {
            $this->id = $id;
            if(is_array($phones)) {
                foreach ($phones as $x) {
                    $this->phones[] = $x;
                }
            }
            else {
                $this->phones[] = $phones;
            }
        }
    }
