-- Create database
-- Added option to check for existing db
CREATE DATABASE IF NOT EXISTS wrenTest;

USE wrenTest;

-- Create table for data
-- Added option to check for existing table
-- Modified naming convention so Symfony can easily process for entity mapping

CREATE TABLE IF NOT EXISTS ProductData (
  intProductDataId int(10) unsigned NOT NULL AUTO_INCREMENT,
  strProductName varchar(50) NOT NULL,
  strProductDesc varchar(255) NOT NULL,
  strProductCode varchar(10) NOT NULL,
  intProductStock int(10) NOT NULL,         -- Added stock
  intProductPrice decimal(13,2) NOT NULL,   -- Added cost/price field (up to 2 decimal places)
  dtmAdded datetime DEFAULT NULL,
  dtmDiscontinued datetime DEFAULT NULL,
  stmTimestamp timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (intProductDataId),
  UNIQUE KEY (strProductCode)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Stores product data';

