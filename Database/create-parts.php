<?php

use Database\MySQLWrapper;

$mysqli = new MySQLWrapper();

$result = $mysqli->query("
    CREATE TABLE IF NOT EXISTS parts (
      id INT PRIMARY KEY AUTO_INCREMENT,
      name VARCHAR(50) NOT NULL,
      description VARCHAR(255),
      price FLOAT NOT NULL,
      quantityInStock INT NOT NULL
    );
");

if ($result === false) throw new Exception('Could not execute query.');
else print("Successfully ran all SQL setup queries." . PHP_EOL);
