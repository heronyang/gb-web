DROP DATABASE IF EXISTS `gb_test`;
CREATE DATABASE `gb_test`;

-- this will create the user if not exists
GRANT ALL ON `gb_test`.* TO 'gb_test'@'localhost' IDENTIFIED BY 'bg';
