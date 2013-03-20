-- Database schema update script #2.
-- $ mysql -u $username -p $database <1.sql

-- tblRideDate is incorrectly titled, it is a log of rides taken, so will be renamed to tblRideLog
RENAME TABLE tblRideDate TO tblRideLog;