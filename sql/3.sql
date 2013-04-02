-- Database schema update script #3.
-- $ mysql -u $username -p $database <3.sql

-- In order to centralise data table creation, create human readable field names (as field comments) for all fields but USER INFORMATION.

ALTER TABLE `tblParkList` CHANGE `idsPark` `idsPark` TINYINT(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Park ID', CHANGE `chrParkName` `chrParkName` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Park Name';

ALTER TABLE `tblRideList` CHANGE `idsRide` `idsRide` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Ride ID', CHANGE `intParkID` `intParkID` TINYINT(3) UNSIGNED NOT NULL COMMENT 'Park ID', CHANGE `chrRideName` `chrRideName` VARCHAR(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Ride Name', CHANGE `ysnTheRide` `ysnTheRide` TINYINT(1) NOT NULL COMMENT '''The'' Ride Prefix', CHANGE `ysnClosed` `ysnClosed` TINYINT(1) NOT NULL COMMENT 'Ride Closed?';

ALTER TABLE `tblRideLog` CHANGE `idsRideDate` `idsRideDate` SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Ride Log ID', CHANGE `intRideID` `intRideID` SMALLINT(5) UNSIGNED NOT NULL COMMENT 'Ride ID', CHANGE `dtmRideDate` `dtmRideDate` VARCHAR(10) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Ride Log Date/Time', CHANGE `intUserID` `intUserID` INT(10) UNSIGNED NOT NULL COMMENT 'User ID', CHANGE `ysnInvalidateRide` `ysnInvalidateRide` TINYINT(1) NOT NULL COMMENT 'Valid Ride?', CHANGE `intSpecialID` `intSpecialID` INT(11) NULL DEFAULT NULL COMMENT 'Ride Log Special ID';

ALTER TABLE `tblRideSpecial` CHANGE `idsRideSpecial` `idsRideSpecial` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Special Ride Type ID', CHANGE `intRideID` `intRideID` INT(11) NOT NULL COMMENT 'Ride IT', CHANGE `intSpecialID` `intSpecialID` INT(11) NOT NULL COMMENT 'Ride Log Special ID';

ALTER TABLE `tblSpecialType` CHANGE `idsSpecialType` `idsSpecialType` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Special Ride Type ID', CHANGE `chrName` `chrName` VARCHAR(45) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Special Ride Type Name', CHANGE `chrShortName` `chrShortName` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL COMMENT 'Special Ride Type Abbreviation'