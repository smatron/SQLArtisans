<?php
/*
|--------------------------------------------------------------------------
|	App File
|--------------------------------------------------------------------------
| This Would create a default App.
|
*/
require 'sql_artisans.php';
require 'config.php';

session_start();

$app = new Sqlartisans($config['driver'],
            $config['host'],
			$config['database'],
			$config['user'],
			$config['pass'],
			$config['port']);

$conn = $app->connect();

if (!$conn ) { die('Problem Connecting to Database, Please Check The Config File.'); }