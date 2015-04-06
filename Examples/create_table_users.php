<?php

require '../app.php';

if (true) {
$app->query('DROP TABLE IF EXISTS users;');

$create_table_users = <<< EOD
CREATE TABLE users (
  uid INT NOT NULL AUTO_INCREMENT,
  username VARCHAR(100) NOT NULL,
  password VARCHAR(250) NOT NULL,
  email VARCHAR(200) NOT NULL,
  regdate VARCHAR(100) NOT NULL,
  reqdate VARCHAR(100) NOT NULL,
  voucher VARCHAR(200) NOT NULL,
  PRIMARY KEY(uid),
  UNIQUE username(username)
);
EOD;

$app->Query($create_table_users);
$app->disconnect();
echo 'Data have been created successfully';
} else {
	echo 'Something went wrong, please check the config file';
}
        
?>