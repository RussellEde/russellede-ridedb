-- Database schema update script #1.
-- $ mysql -u $username -p $database <1.sql

-- As the database is now multi-user, it's possible that two users might want to
-- log a ride at the same time; so dtmRideDate is no longer a unique field.
-- Remove the unique index.
ALTER TABLE tblRideDate DROP INDEX dtmRideDate;

-- Move the front-row / backwards mechanism over into a seperate table for
-- added flexibility. This will allow us to add more types later.

CREATE TABLE tblSpecialType (
  idsSpecialType INT NOT NULL AUTO_INCREMENT,
  chrName VARCHAR(45) NOT NULL,				-- human-readable name
  chrShortName VARCHAR(15) NOT NULL,		-- short name used for div IDs etc
  PRIMARY KEY (idsSpecialType),
  UNIQUE INDEX chrSpecialName_UNIQUE (chrName ASC),
  UNIQUE INDEX chrShortName_UNIQUE (chrShortName ASC)
);

-- some starter values for the special types

insert into tblSpecialType (chrName, chrShortName) values
  ('Front Row', 'front-row'),
  ('Back Row', 'back-row'),
  ('Reverse', 'reverse')
;

-- which rides have which special types available?

CREATE TABLE tblRideSpecial (
  idsRideSpecial INT NOT NULL AUTO_INCREMENT,
  intRideID INT NOT NULL,
  intSpecialID INT NOT NULL,
  PRIMARY KEY (idsRideSpecial)
);

-- migrate special types from the existing table

insert into tblRideSpecial (intRideID, intSpecialID) select
  idsRide, (select idsSpecialType from tblSpecialType where chrShortName = 'front-row')
  from tblRideList
  where ysnFrontRow = 1
;

insert into tblRideSpecial (intRideID, intSpecialID) select
  idsRide, (select idsSpecialType from tblSpecialType where chrShortName = 'reverse')
  from tblRideList
  where ysnReverse = 1
;

-- now remove the old columns from tblRideList

ALTER TABLE tblRideList
  DROP COLUMN ysnReverse, DROP COLUMN ysnFrontRow, DROP INDEX ysnFrontRow
;

-- add the new column to tblRideDate

ALTER TABLE tblRideDate
  ADD COLUMN intSpecialID INT(11) NULL AFTER ysnReverse
;

-- migrate the data into tblRideDate

update tblRideDate
  set intSpecialID = (select idsSpecialType from tblSpecialType where chrShortName = 'front-row')
  where ysnFrontRow = 1
;

update tblRideDate
  set intSpecialID = (select idsSpecialType from tblSpecialType where chrShortName = 'reverse')
  where ysnReverse = 1
;

-- remove the old columns from tblRideDate

ALTER TABLE tblRideDate
  DROP COLUMN ysnReverse, DROP COLUMN ysnFrontRow, DROP INDEX ysnFrontRow
;







