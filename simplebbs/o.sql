use test;
CREATE TABLE users (
user_id INT UNSIGNED NOT NULL AUTO_INCREMENT ,
email VARCHAR(40) NOT NULL ,
password CHAR(40) NOT NULL ,
name VARCHAR(16) NOT NULL ,
sex CHAR(1) NOT NULL ,
sign VARCHAR(200) DEFAULT NULL ,
registration_date DATETIME NOT NULL ,
PRIMARY KEY( user_id ) ,
UNIQUE( email ) ,
UNIQUE( name )
);

CREATE TABLE invitation_unit (
invitation_unit_id INT UNSIGNED NOT NULL AUTO_INCREMENT ,
invitation_id INT UNSIGNED NOT NULL ,
user_id INT UNSIGNED NOT NULL ,
floor SMALLINT UNSIGNED NOT NULL ,
reply_date DATETIME NOT NULL ,
content TEXT NOT NULL ,
PRIMARY KEY( invitation_unit_id )
);

CREATE TABLE invitations (
invitation_id INT UNSIGNED NOT NULL AUTO_INCREMENT ,
user_id INT UNSIGNED NOT NULL ,
last_user_id INT UNSIGNED NOT NULL ,
latest_date DATETIME NOT NULL ,
new_date DATETIME NOT NULL ,
title VARCHAR(100) NOT NULL ,
content TEXT NOT NULL ,
PRIMARY KEY( invitation_id )
);
