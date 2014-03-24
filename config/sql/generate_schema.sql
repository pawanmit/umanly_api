DROP DATABASE IF EXISTS umanly;
CREATE DATABASE umanly;
USE umanly;

CREATE TABLE user
(
  id INT NOT NULL AUTO_INCREMENT,
  email VARCHAR(50) NOT NULL,
  first_name VARCHAR(25) NOT NULL,
  last_name VARCHAR(25) NOT NULL,
  gender VARCHAR(6),
  hometown VARCHAR(100),
  facebook_link VARCHAR(100),
  password VARCHAR(100) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE INDEX user_email_idx (email)
);


CREATE TABLE location (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT,
  longitude DECIMAL(11, 8) NOT NULL DEFAULT -1,
  latitude DECIMAL(10, 8) NOT NULL DEFAULT -1,
  PRIMARY KEY (id),
  FOREIGN KEY (user_id) REFERENCES user(id)
);
