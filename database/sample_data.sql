

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
INSERT INTO customers_addresses (customer_id, country_code, city_id, district, zipcode, street, holding, notes)
VALUES ('3708b3d3cb39', 'BGD', 150, 'Dhaka', '12096', 'Bailey Road', '14', 'Beside the 6 floor building');

COMMIT;