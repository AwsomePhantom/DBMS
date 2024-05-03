

USE dbmsproject;

START TRANSACTION;

-- insert some data

INSERT INTO customers_info VALUES ('2bd445dc114e', 'Bob', 'Ross', '1965-04-23', 'M');
INSERT INTO customers_info VALUES ('3708b3d3cb39', 'Tamim', 'Iqbal', '1986-04-05', 'M');
INSERT INTO customers_info VALUES ('a1b2654c59a6', 'Shakil', 'Hassan', '1985-06-07', 'M');

INSERT INTO customers_contacts (customer_id, phone)
VALUES ('2bd445dc114e', '888-444-6552');
INSERT INTO customers_addresses (customer_id, country_code, city_id, district, zipcode, street, holding, notes)
VALUES ('2bd445dc114e', 'AUS', 131, 'Melbourne', '941001', 'Fleet Street', '45 INT A', 'Near the local pharmacy');
INSERT INTO user_accounts (id, username, password, customer_id)
VALUES ('ea4fc21ecb19', 'bob', '1234', '2bd445dc114e');

INSERT INTO customers_contacts (customer_id, phone)
VALUES ('3708b3d3cb39', '888-614-4889');
INSERT INTO customers_addresses (customer_id, country_code, city_id, district, zipcode, street, holding, notes)
VALUES ('3708b3d3cb39', 'BGD', 150, 'Dhaka', '12096', 'Bailey Road', '14', 'Beside the 6 floor building');
INSERT INTO user_accounts (id, username, password, customer_id)
VALUES ('89af7d8bc91d', 'tamim', '1234', '3708b3d3cb39');

TRUNCATE TABLE post_answers;
TRUNCATE TABLE post_issues;
ALTER TABLE post_answers AUTO_INCREMENT = 0;
ALTER TABLE post_issues AUTO_INCREMENT = 0;

INSERT INTO post_issues (user_id, post_title, post_content, city_id, address)
    VALUES ('ea4fc21ecb19', 'Selling Toyota Allion G Package 2011',
'Brand: Toyota | Model: Allion | Trim / Edition: G Package 2011 | Year of Manufacture: 2011 | Registration year: 2016 | Condition: Used | Transmission: Automatic | Body type: Saloon | Fuel type: Octane, LPG | Engine capacity: 1,500 cc | Kilometers run: 56,000 km | G-Package Push Start.
All Original & Smart Card.
Model:- 2011 | Registration: 2016
Price negotiable', 131, 'Fleet Street');

INSERT INTO `post_answers` (`id`, `post_id`, `user_id`, `content`, `datetime`)
VALUES ('1', '6', '89af7d8bc91d', 'Interested, check inbox!', current_timestamp());

INSERT INTO post_issues (user_id, post_title, post_content, city_id, address)
VALUES ('89af7d8bc91d', ' Repair for BMW 7 Series 2016',
        'Need emergency fix!
Car gear hardened, difficult to start from the 1st. It does a strange grinding sound!
Can bring the car personally, no towing needed', 150, 'Main Street');



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