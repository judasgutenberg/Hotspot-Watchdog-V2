

CREATE TABLE weather_data(
weather_data_id INT AUTO_INCREMENT PRIMARY KEY,
location_id INT NULL,
recorded DATETIME,
temperature DECIMAL(6,3) NULL,
pressure DECIMAL(9,4) NULL,
humidity DECIMAL(6,3) NULL,
wind_direction INT NULL,
precipitation INT NULL,
wind_speed DECIMAL(8,3) NULL,
wind_increment INT NULL,
gas_metric DECIMAL(15,4) NULL
);

CREATE TABLE reboot_log(
  reboot_log_id INT AUTO_INCREMENT PRIMARY KEY,
  location_id INT,
  recorded DATETIME
);

CREATE TABLE location(
  location_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NULL,
  description VARCHAR(2000) NULL,
  device_id INT NULL,
  created DATETIME
);

CREATE TABLE device_type(
  device_type_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NULL,
  description VARCHAR(2000) NULL,
  architecture VARCHAR(100) NULL,
  power_voltage DECIMAL(9,3) NULL,
  created DATETIME
);

CREATE TABLE device(
  device_id INT AUTO_INCREMENT PRIMARY KEY,
  device_type_id INT,
  name VARCHAR(100) NULL,
  description VARCHAR(2000) NULL,
  created DATETIME
);

CREATE TABLE feature_type(
  feature_type_id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NULL,
  description VARCHAR(2000) NULL,
  created DATETIME
);

CREATE TABLE device_type_feature(
  device_type_feature_id INT AUTO_INCREMENT PRIMARY KEY,
  device_type_id INT,
  can_be_input TINYINT,
  can_be_output TINYINT,
  can_be_analog TINYINT,
  name VARCHAR(100) NULL,
  description VARCHAR(2000) NULL,
  created DATETIME
);

CREATE TABLE device_feature(
  device_feature_id INT AUTO_INCREMENT PRIMARY KEY,
  device_type_feature_id INT,
  device_type_id INT,
  value INT NULL,
  name VARCHAR(100) NULL,
  description VARCHAR(2000) NULL,
  created DATETIME,
  modified DATETIME
);



