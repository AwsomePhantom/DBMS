
USE dbmsproject;

SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE active_session;
TRUNCATE user_accounts;

TRUNCATE customers_contacts;
TRUNCATE customers_addresses;
TRUNCATE customers_info;

TRUNCATE businesses_contacts;
TRUNCATE businesses_addresses;
TRUNCATE businesses_info;
SET FOREIGN_KEY_CHECKS = 1;

