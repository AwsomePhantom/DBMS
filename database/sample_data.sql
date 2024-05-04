

USE dbmsproject;

START TRANSACTION;

-- insert some data

INSERT INTO customers_info VALUES ('2bd445dc114e', 'Bob', 'Ross', '1965-04-23', 'M');
INSERT INTO customers_info VALUES ('3708b3d3cb39', 'Ross', 'Spencer', '1986-04-05', 'M');
INSERT INTO customers_info VALUES ('a1b2654c59a6', 'Shakil', 'Hassan', '1985-06-07', 'M');

INSERT INTO customers_contacts (customer_id, phone)
VALUES ('2bd445dc114e', '888-444-6552');
INSERT INTO customers_addresses (customer_id, country_code, city_id, district, zipcode, street, holding, notes)
VALUES ('2bd445dc114e', 'BGD', 159, 'Jessore', '941001', 'Fleet Street', '45 INT A', 'Near the local pharmacy');
INSERT INTO user_accounts (id, username, email, password, customer_id)
VALUES ('ea4fc21ecb19', 'bob', 'bob@email.com', '1234', '2bd445dc114e');

INSERT INTO customers_contacts (customer_id, phone)
VALUES ('3708b3d3cb39', '888-614-4889');
INSERT INTO customers_addresses (customer_id, country_code, city_id, district, zipcode, street, holding, notes)
VALUES ('3708b3d3cb39', 'BGD', 150, 'Dhaka', '12096', 'Bailey Road', '14', 'Beside the 6 floor building');


INSERT INTO businesses_info (id, owner_id, company_name, company_type, licence_number, office_hour_start, office_hour_end, office_weekdays)
VALUES ('70f4870ccc33', '3708b3d3cb39', 'Car Mechanics Corp', 'Engine, Oil, A/C, Seats, Chassis', '1164-1554-1555', TIME('08:30:00'), TIME('20:00:00'), 'MON,TUE,WED,THU,FRI,SAT');
INSERT INTO businesses_contacts (business_id, phone)
VALUES ('70f4870ccc33', '848-617-4589');
INSERT INTO businesses_addresses (business_id, country_code, city_id, district, zipcode, street, holding, notes)
VALUES ('70f4870ccc33', 'BGD', 150, 'Dhaka', '12096', 'Bailey Road', '20', 'Beside the 6 floor building');
INSERT INTO user_accounts (id, username, email, password, customer_id, business_id)
VALUES ('89af7d8bc91d', 'ross', 'ross@email.com', '1234', '3708b3d3cb39', '70f4870ccc33');

INSERT INTO post_issues (user_id, post_title, post_content, city_id, address)
    VALUES ('ea4fc21ecb19', 'Selling Toyota Allion G Package 2011',
'Brand: Toyota | Model: Allion | Trim / Edition: G Package 2011 | Year of Manufacture: 2011 | Registration year: 2016 | Condition: Used | Transmission: Automatic | Body type: Saloon | Fuel type: Octane, LPG | Engine capacity: 1,500 cc | Kilometers run: 56,000 km | G-Package Push Start.
All Original & Smart Card.
Model:- 2011 | Registration: 2016
Price negotiable', 131, 'Fleet Street');

INSERT INTO `post_answers` (`id`, `post_id`, `user_id`, `content`, `datetime`)
VALUES ('1', '1', '89af7d8bc91d', 'Interested, check inbox!', current_timestamp());

INSERT INTO post_issues (user_id, post_title, post_content, city_id, address)
VALUES ('89af7d8bc91d', ' Repair for BMW 7 Series 2016',
        'Need emergency fix!
Car gear hardened, difficult to start from the 1st. It does a strange grinding sound!
Can bring the car personally, no towing needed', 150, 'Main Street');

INSERT INTO incident_reports (id, user_id, gpsx, gpsy, country_code, district, street, message_title, message)
VALUES (1, 'ea4fc21ecb19', '23.725700378418', '90.402603149414', 'BGD', 'Dhaka', 'Quater Street', 'Smoke from the engine', 'Need towing and a good mechanic!');
INSERT INTO incident_reports (id, user_id, gpsx, gpsy, country_code, district, street, message_title, message)
VALUES (2, 'ea4fc21ecb19', '23.725700378418', '90.402603149414', 'BGD', 'Dhaka', 'Highway B15', 'Out of fuel!', 'I\'m out of fuel!');

COMMIT;

-- Random IDS
-- ea4fc21ecb19
-- 89af7d8bc91d
-- 078c7bb7829d
-- deabfe55be0a
-- 621f78ae5a55
-- a7c43467f15b
-- 70f4870ccc33
-- 66d8e6e5f30f
-- 933b987f7fd9
-- 297a2dfd634e