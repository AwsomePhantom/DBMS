
DROP DATABASE IF EXISTS dbmsproject;
CREATE DATABASE dbmsproject;
USE dbmsproject;

-- --------------------- Basic Attributes --------------------------------------


-- Base class person, list of persons who registered including users and businesses
CREATE TABLE person (
                        id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                        name varchar(255) DEFAULT NULL,
                        lastname varchar(255) DEFAULT NULL,
                        birthdate date DEFAULT NULL,
                        gender character DEFAULT NULL
                        -- contacts and addresses are multivalued and have person_id on their table
);

-- add company type table

-- business (Derived) > person (Base)
CREATE TABLE business (
                          id int NOT NULL PRIMARY KEY, -- use it as a unique identifier randomly generated
                          person_id int NOT NULL,
                          company_name varchar(255) NOT NULL,
                          company_type varchar(255) DEFAULT NULL, -- painting, chassis, ...
                          licence_number varchar(255) NOT NULL,    -- VAT licence
                          office_hour_start time NOT NULL,
                          office_hour_end time NOT NULL,
                          office_weekdays varchar(50),    -- {SUN, MON, TUE, WED, THU, FRI, SAT}
                          active boolean NOT NULL DEFAULT TRUE,       -- if the business account has been deactivated or not
                          registration_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (person_id) REFERENCES person(id)
);

-- list of addresses of registered users, multivalued weak entity set
CREATE TABLE contacts (
                          id int PRIMARY KEY AUTO_INCREMENT,
                          person_id int NOT NULL,
                          phone VARCHAR(15) NOT NULL,
                          FOREIGN KEY (person_id) REFERENCES person(id)
                          -- the email address is set only in the user_account table
);

-- multivalued weak entity set
CREATE TABLE address (
                         id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         person_id int NOT NULL,
                         -- country_id varchar(255) DEFAULT NULL,
                         -- city_id varchar(255) DEFAULT NULL,     -- major cities' name
                         -- district varchar(255) DEFAULT NULL,    -- county name
                         -- street varchar(255) DEFAULT NULL,
                         -- holding int DEFAULT NULL,
                         address varchar(255),
                         note longtext DEFAULT NULL,     -- extra details
                         FOREIGN KEY (person_id) REFERENCES person(id)
);


-- ------------------- Accounting Section -----------------------

-- create at the end of the intervention to close transactions
CREATE TABLE financial_statement (
                                 id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                 start_date datetime NOT NULL,
                                 end_date datetime NOT NULL,
                                 issue_date datetime NOT NULL ON UPDATE NOW(),

                                 customer_id int NOT NULL,
                                 business_id int NOT NULL,

                                 location_addr int NOT NULL, -- set it to NULL
                                 advance_payment numeric(10, 2) DEFAULT NULL,
                                 discount float default NULL,
                                 total_expense numeric(10, 2) NOT NULL,
                                 total_revenue numeric(10, 2) NOT NULL,
                                 net_income numeric(10, 2) NOT NULL,
                                 CONSTRAINT statement_person FOREIGN KEY(customer_id) REFERENCES person(id),
                                 CONSTRAINT statement_business FOREIGN KEY(business_id) REFERENCES business(id)
);

-- Base class account, single elementary entries and jobs, on new entries update history_revenue
CREATE TABLE financial_account (
                         id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         date datetime NOT NULL,

                         person_id int NOT NULL,
                         business_id int NOT NULL,

                         parts_expense numeric(10, 2),  -- price of the replacement component
                         vehicle_parts_desc varchar(255) DEFAULT NULL,   -- replacement of vehicle part description

                         vehicle_service_desc varchar(255), -- description of works to be done or doing
                         service_revenue numeric(10, 2),    -- estimation for the work


                         notes longtext DEFAULT NULL,
                         sub_total numeric(10, 2),
                         CONSTRAINT account_person FOREIGN KEY(person_id) REFERENCES person(id),
                         CONSTRAINT account_business FOREIGN KEY(business_id) REFERENCES business(id)
);

-- ---------------------- Social network class related -------------------------------------

CREATE TABLE user_account (
                              id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                              person_id int NOT NULL,
                              business_account bool DEFAULT FALSE,  -- if true fetch from business where person_id
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

CREATE TABLE incident_location (
                                id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                                user_id int NOT NULL,
                                GPSX numeric(10, 7),
                                GPSY numeric(10, 7),
                                country_id varchar(255) DEFAULT NULL,
                                city_id varchar(255) DEFAULT NULL,     -- major cities' name
                                district varchar(255) DEFAULT NULL,    -- county name
                                street varchar(255) DEFAULT NULL,
                                note longtext DEFAULT NULL,     -- extra details
                                datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                FOREIGN KEY (user_id) REFERENCES user_account(id)
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

