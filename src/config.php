<?php

 // Change the following to your actual server and database
 $db_servername = "localhost";
 $db_username = "root";
 $db_password = "";
 $db_name = "register_login_system";
 
 $system_name = "PHP & MySQL <br/> Register & Login Template";
 $initial_table_name = "users";

 /*
 SQL Query (Manual):

 CREATE TABLE IF NOT EXISTS table_name (
   id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
   first_name VARCHAR(100) NOT NULL,
   middle_name VARCHAR(100) NOT NULL,
   last_name VARCHAR(100) NOT NULL,
   birth_date DATE NOT NULL,
   username VARCHAR(100) NOT NULL UNIQUE,
   email VARCHAR(100) NOT NULL UNIQUE,
   password VARCHAR(200) NOT NULL,
   date_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
   latest_login_ip_address VARCHAR(45),
   latest_login_session TIMESTAMP DEFAULT CURRENT_TIMESTAMP
 );
 ALTER TABLE table_name
 AUTO_INCREMENT = 1001;

 */
?>