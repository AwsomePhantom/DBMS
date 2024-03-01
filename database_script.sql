
DROP DATABASE IF EXISTS dbmsproject;
CREATE DATABASE dbmsproject;
USE dbmsproject;

-- --------------------- Basic Attributes --------------------------------------

-- list of addresses of registered users
CREATE TABLE address (
                         id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         country_id varchar(255) DEFAULT NULL,
                         city_id varchar(255) DEFAULT NULL,
                         district varchar(255) DEFAULT NULL,
                         street varchar(255) DEFAULT NULL,
                         postcode varchar(10) DEFAULT NULL,
                         holding int DEFAULT NULL,
                         intern varchar(10) DEFAULT NULL,
                         note longtext DEFAULT NULL
);

-- Base class person, list of persons who registered including users and businesses
CREATE TABLE person (
                        id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                        name varchar(255) DEFAULT NULL,
                        lastname varchar(255) DEFAULT NULL,
                        birthdate date DEFAULT NULL,
                        gender character DEFAULT NULL,
                        address_id int NOT NULL,
                        address_id2 int,
                        phone1 VARCHAR(15) NOT NULL,
                        phone2 varchar(15),
                        email varchar(255) NOT NULL,
                        CONSTRAINT person_address FOREIGN KEY(address_id) REFERENCES address(id),
                        CONSTRAINT person_address2 FOREIGN KEY(address_id2) REFERENCES address(id)
);

-- add company type table

-- business (Derived) > person (Base)
CREATE TABLE business (
                          id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                          unique_identifier varchar(255) UNIQUE NOT NULL, -- for the final statement
                          owner_id int NOT NULL,  -- is the person table entry for the business
                          company_name varchar(255) NOT NULL,
                          company_address_id int NOT NULL,
                          company_type varchar(255),
                          phone1 VARCHAR(15) NOT NULL,
                          phone2 varchar(15),
                          email varchar(255) NOT NULL,
                          licence_number varchar(255),    -- VAT licence
                          office_hour_start time NOT NULL,
                          office_hour_end time NOT NULL,
                          office_weekdays varchar(50),    -- {SUN, MON, TUE, WED, THU, FRI, SAT}
                          active boolean NOT NULL DEFAULT TRUE,       -- if the business has been unactivated
                          registration_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          CONSTRAINT business_person FOREIGN KEY(owner_id) REFERENCES address(id)
);

-- ------------------- Accounting Section -----------------------

-- create at the end of the intervention to close transactions
CREATE TABLE final_statement (
                                 id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                 start_date datetime NOT NULL,
                                 end_date datetime NOT NULL,
                                 issue_date datetime NOT NULL ON UPDATE NOW(),
                                 person_id int NOT NULL,
                                 business_id int NOT NULL,
                                 location_addr int NOT NULL, -- set it to NULL
                                 advance_payment numeric(10, 2) DEFAULT NULL,
                                 discount float default NULL,
                                 total_expense numeric(10, 2) NOT NULL,
                                 total_revenue numeric(10, 2) NOT NULL,
                                 net_income numeric(10, 2) NOT NULL,
                                 CONSTRAINT statement_person FOREIGN KEY(person_id) REFERENCES person(id),
                                 CONSTRAINT statement_business FOREIGN KEY(business_id) REFERENCES business(id),
                                 CONSTRAINT statement_address FOREIGN KEY(business_id) REFERENCES address(id)
);

-- Base class account, single elementary entries and jobs, on new entries update history_revenue
CREATE TABLE account (
                         id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         statement_id int NOT NULL,
                         date datetime NOT NULL,
                         person_id int NOT NULL,
                         business_id int NOT NULL,
                         vehicle_service_desc varchar(255),
                         service_revenue numeric(10, 2),
                         vehicle_parts_desc varchar(255),
                         parts_expense numeric(10, 2),
                         notes longtext DEFAULT NULL,
                         sub_total numeric(10, 2),
                         CONSTRAINT account_person FOREIGN KEY(person_id) REFERENCES person(id),
                         CONSTRAINT account_business FOREIGN KEY(business_id) REFERENCES business(id),
                         CONSTRAINT account_statement FOREIGN KEY(statement_id) REFERENCES final_statement(id)   -- update the statement each time
);

-- ---------------------- Social network class related -------------------------------------

CREATE TABLE user_account (
                              id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                              person_id int NOT NULL,
                              business_identifier varchar(255) DEFAULT NULL,    -- if user is a business
                              username varchar(255) UNIQUE NOT NULL,
                              password varchar(255) NOT NULL,
                              last_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              last_logged timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                              registration_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              CONSTRAINT user_person FOREIGN KEY(person_id) REFERENCES person(id)
);

CREATE TABLE follower (
                              id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                              user_a int NOT NULL,
                              user_b int NOT NULL,
                              CONSTRAINT follower_user_a FOREIGN KEY (user_a) REFERENCES user_account(id),
                              CONSTRAINT follower_user_b FOREIGN KEY (user_a) REFERENCES user_account(id)
);

CREATE TABLE rescue_outdoor (
                                id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                user_id int NOT NULL,
                                GPSX numeric(10, 7),
                                GPSY numeric(10, 7),
                                location_addr int NOT NULL,
                                datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                CONSTRAINT rescue_user FOREIGN KEY (user_id) REFERENCES user_account(id)
);

-- social network post
CREATE TABLE post_issue (
                            id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                            user_id int NOT NULL,
                            post_title LONGTEXT NOT NULL,
                            post_content LONGTEXT,
                            datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            last_seen TIMESTAMP DEFAULT NULL,
                            CONSTRAINT post_user FOREIGN KEY (user_id) REFERENCES user_account(id)
);

-- social post answers
CREATE TABLE post_answer (
                             id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                             post_id int NOT NULL,
                             user_id int NOT NULL,
                             content LONGTEXT,
                             datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                             CONSTRAINT answer_post FOREIGN KEY (post_id) REFERENCES post_issue(id),
                             CONSTRAINT answer_user FOREIGN KEY (user_id) REFERENCES user_account(id)
);

-- ------------------- Web related ----------------------------

