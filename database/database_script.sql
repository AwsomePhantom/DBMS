START TRANSACTION;

DROP DATABASE IF EXISTS dbmsproject;
CREATE DATABASE dbmsproject;
USE dbmsproject;

-- --------------------- Geolocation --------------------------------------

CREATE TABLE countries (
                           code char(3) PRIMARY KEY,
                           name varchar(52) NOT NULL,
                           continent enum('Asia','Europe','North America','Africa','Oceania','Antarctica','South America') NOT NULL DEFAULT 'Asia',
                           region varchar(26) NOT NULL,
                           capital_city_code int(11) DEFAULT NULL,
                           code2 char(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE cities (
                        id int PRIMARY KEY AUTO_INCREMENT,
                        name varchar(35) NOT NULL,
                        country_code char(3) NOT NULL,
                        district varchar(20) NOT NULL,
                        CONSTRAINT cities_country_code FOREIGN KEY (country_code) REFERENCES countries(code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------- Basic Attributes --------------------------------------


-- Base class customer, list of customers who registered including users and businesses
CREATE TABLE customers_info (
                           id char(12) PRIMARY KEY,
                           name varchar(255) DEFAULT NULL,
                           lastname varchar(255) DEFAULT NULL,
                           birthdate date DEFAULT NULL,
                           gender character DEFAULT NULL
                           -- contacts and addresses are multivalued and have customer_id on their table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- add company type table

-- business (Derived) > customer (Base)
CREATE TABLE businesses_info (
                          id CHAR(12) PRIMARY KEY, -- use it as a unique identifier randomly generated
                          owner_id char(12) NOT NULL,    -- customer table
                          company_name varchar(255) NOT NULL,
                          company_type varchar(255) DEFAULT NULL, -- painting, chassis, ...
                          licence_number varchar(255) NOT NULL,    -- VAT licence
                          office_hour_start time NOT NULL,
                          office_hour_end time NOT NULL,
                          office_weekdays varchar(26),    -- {SUN, MON, TUE, WED, THU, FRI, SAT}
                          active boolean NOT NULL DEFAULT TRUE,       -- if the business account has been deactivated or not
                          registration_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (owner_id) REFERENCES customers_info(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- list of addresses of registered users, multivalued weak entity set
CREATE TABLE customers_contacts (
                          id int PRIMARY KEY AUTO_INCREMENT,    -- auto generated id
                          customer_id char(12) NOT NULL,
                          phone varchar(15) NOT NULL,
                          FOREIGN KEY (customer_id) REFERENCES customers_info(id)
                          -- the email address is set only in the user_account table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- list of addresses of registered users, multivalued weak entity set
CREATE TABLE businesses_contacts (
                          id int PRIMARY KEY AUTO_INCREMENT,    -- auto generated id
                          business_id char(12) NOT NULL,
                          phone VARCHAR(15) NOT NULL,
                          FOREIGN KEY (business_id) REFERENCES businesses_info(id)
    -- the email address is set only in the user_account table
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- multivalued weak entity set
CREATE TABLE customers_addresses (
                         id int PRIMARY KEY AUTO_INCREMENT, -- auto generated id
                         customer_id char(12) NOT NULL,
                         country_code char(3) NOT NULL,
                         city_id int NOT NULL,     -- major cities' name
                         district varchar(20) NOT NULL,    -- county name
                         zipcode varchar(10) DEFAULT NULL,
                         street varchar(255) DEFAULT NULL, -- address
                         holding varchar(10) DEFAULT NULL,
                         notes longtext DEFAULT NULL,     -- extra details
                         FOREIGN KEY (customer_id) REFERENCES customers_info(id),
                         FOREIGN KEY (country_code) REFERENCES countries(code),
                         FOREIGN KEY (city_id) REFERENCES cities(id)

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE businesses_addresses (
                                    id int PRIMARY KEY AUTO_INCREMENT, -- auto generated id
                                    business_id char(12) NOT NULL,
                                    country_code char(3) DEFAULT NULL,
                                    city_id int NOT NULL,     -- major cities' name
                                    district varchar(20) DEFAULT NULL,    -- county name
                                    zipcode varchar(10) DEFAULT NULL,
                                    street varchar(255) DEFAULT NULL, -- address
                                    holding varchar(10) DEFAULT NULL,
                                    notes longtext DEFAULT NULL,     -- extra details
                                    FOREIGN KEY (business_id) REFERENCES businesses_info(id),
                                    FOREIGN KEY (country_code) REFERENCES countries(code),
                                    FOREIGN KEY (city_id) REFERENCES cities(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ------------------- Accounting Section -----------------------

-- create at the end of the intervention to close transactions
CREATE TABLE financial_statements (
                                 id int PRIMARY KEY AUTO_INCREMENT, -- auto generated id
                                 start_date datetime NOT NULL DEFAULT NOW(),
                                 end_date datetime DEFAULT NULL,
                                 delivery_date datetime DEFAULT NULL,

                                 customer_id char(12) NOT NULL,
                                 business_id char(12) NOT NULL,

                                 location_addr varchar(255) DEFAULT NULL,
                                 advance_payment float(10, 2) DEFAULT 0.00,
                                 total_payment float(10, 2) DEFAULT 0.00,
                                 discount float(5, 2) DEFAULT 0.00,
                                 total_expense float(10, 2) DEFAULT 0.00,
                                 net_product float(10, 2) DEFAULT 0.00,
                                 tax float(3, 2) DEFAULT 0.15,
                                 gross_income float(10, 2) DEFAULT 0.00,
                                 status varchar(5) DEFAULT 'OPEN',
                                 CONSTRAINT statement_customer FOREIGN KEY(customer_id) REFERENCES customers_info(id),
                                 CONSTRAINT statement_business FOREIGN KEY(business_id) REFERENCES businesses_info(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Base class account, single elementary entries and jobs, on new entries update history_revenue
CREATE TABLE financial_accounts (
                         id int NOT NULL PRIMARY KEY AUTO_INCREMENT,    -- auto generated id
                         statement_id int NOT NULL,
                         date datetime NOT NULL,

                         customer_id char(12) NOT NULL,
                         business_id char(12) NOT NULL,

                         parts_expense float(10, 2) DEFAULT 0.00,  -- price of the replacement component
                         vehicle_parts_desc varchar(255) DEFAULT NULL,   -- replacement of vehicle part description

                         vehicle_service_desc varchar(255) DEFAULT NULL, -- description of works to be done or doing
                         service_revenue float(10, 2) DEFAULT 0.00,    -- estimation for the work


                         notes longtext DEFAULT NULL,
                         sub_total float(10, 2) DEFAULT 0.00,
                         CONSTRAINT account_client FOREIGN KEY(customer_id) REFERENCES customers_info(id),
                         CONSTRAINT account_business FOREIGN KEY(business_id) REFERENCES businesses_info(id),
                         CONSTRAINT account_statement FOREIGN KEY(statement_id) REFERENCES financial_statements(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ---------------------- Social network class related -------------------------------------

CREATE TABLE user_accounts (
                              id char(12) PRIMARY KEY,  -- random php generated id
                              username varchar(255) UNIQUE,
                              password varchar(255) NOT NULL,
                              email varchar(255) NOT NULL,
                              customer_id char(12) NOT NULL,
                              business_id char(12) DEFAULT NULL, -- add check if business id exists

                              last_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              last_logged timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                              registration_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              theme varchar(100) DEFAULT NULL,
                              CONSTRAINT user_customer FOREIGN KEY(customer_id) REFERENCES customers_info(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Active user logged in
CREATE TABLE active_session (
                                id char(20) PRIMARY KEY,
                                username varchar(255) NOT NULL,
                                start TIMESTAMP DEFAULT NOW()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE followers (
                              user_a char(12) NOT NULL,
                              user_b char(12) NOT NULL,
                              CONSTRAINT followers_user_a FOREIGN KEY (user_a) REFERENCES user_accounts(id),
                              CONSTRAINT followers_user_b FOREIGN KEY (user_a) REFERENCES user_accounts(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE incident_reports (
                                id int PRIMARY KEY AUTO_INCREMENT,  -- auto generated id
                                user_id char(12) NOT NULL,
                                gpsx float(20, 14) DEFAULT NULL,
                                gpsy float(20, 14) DEFAULT NULL,
                                country_code varchar(3) DEFAULT NULL,
                                district varchar(255) DEFAULT NULL,    -- county name
                                street varchar(255) DEFAULT NULL,
                                message_title varchar(255) DEFAULT NULL,
                                message longtext DEFAULT NULL,     -- extra details
                                datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                FOREIGN KEY (user_id) REFERENCES user_accounts(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- social network post
CREATE TABLE post_issues (
                            id int PRIMARY KEY AUTO_INCREMENT,  -- auto generated id
                            user_id char(12) NOT NULL,
                            post_title LONGTEXT NOT NULL,
                            post_content LONGTEXT,
                            city_id int,
                            address varchar(255),
                            date_time TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                            -- last_seen TIMESTAMP NULL DEFAULT NULL,
                            CONSTRAINT post_user FOREIGN KEY (user_id) REFERENCES user_accounts(id),
                            CONSTRAINT post_city FOREIGN KEY (city_id) REFERENCES cities(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- social post answers
CREATE TABLE post_answers (
                             id int PRIMARY KEY AUTO_INCREMENT, -- auto generated id
                             post_id int NOT NULL,
                             user_id char(12) NOT NULL,
                             content LONGTEXT,
                             datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                             CONSTRAINT answer_post FOREIGN KEY (post_id) REFERENCES post_issues(id),
                             CONSTRAINT answer_user FOREIGN KEY (user_id) REFERENCES user_accounts(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
-- create new incident for social post services
CREATE TABLE list_repair_requests (
                            id int PRIMARY KEY AUTO_INCREMENT,
                            incident_id int NOT NULL,
                            user_id char(12) NOT NULL,
                            datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            CONSTRAINT repair_request_post_id FOREIGN KEY (incident_id) REFERENCES incident_reports(id),
                            CONSTRAINT repair_request_user FOREIGN KEY (user_id) REFERENCES user_accounts(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ------------------- Web related ----------------------------


-- ------------------- Triggers --------------------------------

COMMIT;

