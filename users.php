<?php

class Person {
    public $name, $lastname, $birthdate, $gender;
    function __construct($name, $lastname, $birthdate, $gender) {
        $this->name = $name;
        $this->$lastname = $lastname;
        $this->birthdate = $birthdate;
        $this->gender = $gender;
    }

    function copy(Person $p) : void {
        $this->name = $p->name;
        $this->lastname = $p->lastname;
        $this->birthdate = $p->birthdate;
        $this->gender = $p->gender;
    }
}

class Business extends Person {
    public $UID, $company, $active;
    public $officehours = array(
      "open" => null,
      "closed" => null,
      "weekdays" =>
      array("SUN", "MON", "TUE", "WED", "THU", "FRI", "SAT")
    );

    function __construct($person, $UID, $company, $officehours, $active) {
        if(!($person instanceof Person)) {
            debug("b not an instance Business object. Line: " . __LINE__);
            return;
        }
        parent::copy($person);
        $this->UID = $UID;
        $this->company = $company;
        $this->$officehours = clone $officehours;
        $this->active = $active;
    }

    function copy($b) : void {
        if(!($b instanceof Business)) {
            debug("b not an instance Business object. Line: " . __LINE__);
            return;
        }
        $this->name = $b->name;
        $this->lastname = $b->lastname;
        $this->birthdate = $b->birthdate;
        $this->gender = $b->gender;
        $this->UID = $b->UID;
        $this->company = $b->company;
        $this->officehours = $b->officehours;
    }
    function is_available() : bool {
        // check opening hours using time libraries
        return false;
    }
}

class User {
    public $entity, $username;
    public $registration_date, $last_logged;
    function __construct($entity, $username, $registration_date, $last_logged) {
        $this->entity = $entity;
        $this->username = $username;
        $this->registration_date = $registration_date;
        $this->last_logged = $last_logged;
    }

    function is_business() : bool {
        return ($this->entity instanceof Business);
    }
}
