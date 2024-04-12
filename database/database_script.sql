
DROP DATABASE IF EXISTS dbmsproject;
CREATE DATABASE dbmsproject;
USE dbmsproject;

-- --------------------- Basic Attributes --------------------------------------


-- Base class customer, list of customers who registered including users and businesses
CREATE TABLE customers_info (
                        id CHAR(8) PRIMARY KEY,
                        name varchar(255) DEFAULT NULL,
                        lastname varchar(255) DEFAULT NULL,
                        birthdate date DEFAULT NULL,
                        gender character DEFAULT NULL
                        -- contacts and addresses are multivalued and have customer_id on their table
);

-- add company type table

-- business (Derived) > customer (Base)
CREATE TABLE businesses_info (
                          id CHAR(8) PRIMARY KEY, -- use it as a unique identifier randomly generated
                          owner_id char(8) NOT NULL,    -- customer table
                          company_name varchar(255) NOT NULL,
                          company_type varchar(255) DEFAULT NULL, -- painting, chassis, ...
                          licence_number varchar(255) NOT NULL,    -- VAT licence
                          office_hour_start time NOT NULL,
                          office_hour_end time NOT NULL,
                          office_weekdays varchar(50),    -- {SUN, MON, TUE, WED, THU, FRI, SAT}
                          active boolean NOT NULL DEFAULT TRUE,       -- if the business account has been deactivated or not
                          registration_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (owner_id) REFERENCES customers_info(id)
);

-- list of addresses of registered users, multivalued weak entity set
CREATE TABLE customers_contacts (
                          id int PRIMARY KEY AUTO_INCREMENT,
                          customer_id char(8) NOT NULL,
                          phone varchar(15) NOT NULL,
                          FOREIGN KEY (customer_id) REFERENCES customers_info(id)
                          -- the email address is set only in the user_account table
);

-- list of addresses of registered users, multivalued weak entity set
CREATE TABLE businesses_contacts (
                          id int PRIMARY KEY AUTO_INCREMENT,
                          business_id char(8) NOT NULL,
                          phone VARCHAR(15) NOT NULL,
                          FOREIGN KEY (business_id) REFERENCES businesses_info(id)
    -- the email address is set only in the user_account table
);

-- multivalued weak entity set
CREATE TABLE customers_addresses (
                         id int PRIMARY KEY AUTO_INCREMENT, -- use stronger primary key instead of autoincrement for multi-user
                         customer_id char(8) NOT NULL,
                         country_code varchar(5) DEFAULT NULL,
                         city varchar(255) DEFAULT NULL,     -- major cities' name
                         district varchar(255) DEFAULT NULL,    -- county name
                         zipcode varchar(10) DEFAULT NULL,
                         street varchar(255) DEFAULT NULL, -- address
                         holding int DEFAULT NULL,
                         notes longtext DEFAULT NULL,     -- extra details
                         FOREIGN KEY (customer_id) REFERENCES customers_info(id)
);

CREATE TABLE businesses_addresses (
                                    id int PRIMARY KEY AUTO_INCREMENT, -- use stronger primary key instead of autoincrement for multi-user
                                    business_id char(8) NOT NULL,
                                    country_code varchar(5) DEFAULT NULL,
                                    city varchar(255) DEFAULT NULL,     -- major cities' name
                                    district varchar(255) DEFAULT NULL,    -- county name
                                    zipcode varchar(10) DEFAULT NULL,
                                    street varchar(255) DEFAULT NULL, -- address
                                    holding int DEFAULT NULL,
                                    notes longtext DEFAULT NULL,     -- extra details
                                    FOREIGN KEY (business_id) REFERENCES businesses_info(id)
);


-- ------------------- Accounting Section -----------------------

-- create at the end of the intervention to close transactions
CREATE TABLE financial_statements (
                                 id int PRIMARY KEY AUTO_INCREMENT,
                                 start_date datetime NOT NULL,
                                 end_date datetime NOT NULL,
                                 issue_date datetime NOT NULL ON UPDATE NOW(),

                                 customer_id char(8) NOT NULL,
                                 business_id char(8) NOT NULL,

                                 location_addr int NOT NULL, -- set it to NULL
                                 advance_payment numeric(10, 2) DEFAULT NULL,
                                 discount float default NULL,
                                 total_expense numeric(10, 2) NOT NULL,
                                 total_revenue numeric(10, 2) NOT NULL,
                                 net_income numeric(10, 2) NOT NULL,
                                 CONSTRAINT statement_customer FOREIGN KEY(customer_id) REFERENCES customers_info(id),
                                 CONSTRAINT statement_business FOREIGN KEY(business_id) REFERENCES businesses_info(id)
);

-- Base class account, single elementary entries and jobs, on new entries update history_revenue
CREATE TABLE financial_accounts (
                         id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                         date datetime NOT NULL,

                         customer_id char(8) NOT NULL,
                         business_id char(8) NOT NULL,

                         parts_expense numeric(10, 2),  -- price of the replacement component
                         vehicle_parts_desc varchar(255) DEFAULT NULL,   -- replacement of vehicle part description

                         vehicle_service_desc varchar(255), -- description of works to be done or doing
                         service_revenue numeric(10, 2),    -- estimation for the work


                         notes longtext DEFAULT NULL,
                         sub_total numeric(10, 2),
                         CONSTRAINT account_client FOREIGN KEY(customer_id) REFERENCES customers_info(id),
                         CONSTRAINT account_business FOREIGN KEY(business_id) REFERENCES businesses_info(id)
);

-- ---------------------- Social network class related -------------------------------------

CREATE TABLE user_accounts (
                              id char(8) PRIMARY KEY,
                              username varchar(255) UNIQUE,
                              password varchar(255) NOT NULL,
                              customer_id char(8) NOT NULL,
                              business_id char(8) DEFAULT FALSE, -- add check if business id exists

                              last_modified timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              last_logged timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                              registration_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              CONSTRAINT user_customer FOREIGN KEY(customer_id) REFERENCES customers_info(id)
);

CREATE TABLE followers (
                              user_a char(8) NOT NULL,
                              user_b char(8) NOT NULL,
                              CONSTRAINT followers_user_a FOREIGN KEY (user_a) REFERENCES user_accounts(id),
                              CONSTRAINT followers_user_b FOREIGN KEY (user_a) REFERENCES user_accounts(id)
);

CREATE TABLE incident_locations (
                                id int PRIMARY KEY AUTO_INCREMENT,
                                user_id char(8) NOT NULL,
                                GPSX numeric(10, 7),
                                GPSY numeric(10, 7),
                                country_id varchar(255) DEFAULT NULL,
                                city_id varchar(255) DEFAULT NULL,     -- major cities' name
                                district varchar(255) DEFAULT NULL,    -- county name
                                street varchar(255) DEFAULT NULL,
                                note longtext DEFAULT NULL,     -- extra details
                                datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                                FOREIGN KEY (user_id) REFERENCES user_accounts(id)
);

-- social network post
CREATE TABLE post_issues (
                            id int PRIMARY KEY AUTO_INCREMENT,
                            user_id char(8) NOT NULL,
                            post_title LONGTEXT NOT NULL,
                            post_content LONGTEXT,
                            datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                            last_seen TIMESTAMP DEFAULT NULL,
                            CONSTRAINT post_user FOREIGN KEY (user_id) REFERENCES user_accounts(id)
);

-- social post answers
CREATE TABLE post_answers (
                             id int PRIMARY KEY AUTO_INCREMENT,
                             post_id int NOT NULL,
                             user_id char(8) NOT NULL,
                             content LONGTEXT,
                             datetime TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                             CONSTRAINT answer_post FOREIGN KEY (post_id) REFERENCES post_issues(id),
                             CONSTRAINT answer_user FOREIGN KEY (user_id) REFERENCES user_accounts(id)
);

-- ------------------- Web related ----------------------------


-- ------------------- Triggers --------------------------------


-- insert some data

INSERT INTO customers_info VALUES ('AAAAAAAA', 'rabinul', 'islam', '1992-11-10', 'M');
INSERT INTO customers_info VALUES ('ABABABAB', 'tamim', 'player', '1945-04-05', 'M');
INSERT INTO customers_info VALUES ('CACACACA', 'shakil', 'sheraton', '1985-06-07', 'M');

INSERT INTO customers_contacts (customer_id, phone)
VALUES ('AAAAAAAA', '888-444-6552');
INSERT INTO customers_addresses (customer_id, country_code, city, district, street, holding, notes)
VALUES ('AAAAAAAA', 'AJB', 'DOHA', 'KERALA', 'Street Fleet', 18, 'Near the local pharmacy');
