<?php

/*
|--------------------------------------------------------------------------
| Database Connection
|--------------------------------------------------------------------------
|*Use this Drivers to Access Database Server Easily*
|-----------------------------------------------
|Drivers  ->    Database Servers
|-----------------------------------------------
|mysql 	  ->	MySQL
|sqlite   ->	SQLite
|sqlsrv   ->	Microsoft SQL Server
|mssql 	  ->	Microsoft SQL Server(Works in Windows and linux)
|odbc	  ->	Microsoft Access
|pg 	  ->	PostgreSQL
|oracle	  ->	ORACLE
|fbd	  ->	Firebird
*/

/*
|--------------------------------------------------------------------------
| Connect to MySQL Server
|--------------------------------------------------------------------------
*/
$config = array(
		'driver' 	=> 'mysql',
		'host' 		=> 'localhost',
		'database'  => 'blog',
		'user' 		=> 'root',
		'pass' 		=> 'pass',
		'port' 		=> ''
);

/*
|--------------------------------------------------------------------------
| Connect to MySQL Server
|--------------------------------------------------------------------------
*/
// $config = array(
// 		'driver' 	=> 'DRVER',
// 		'host' 		=> 'LOCALHOST',
// 		'database'  => 'DATABASE NAME',
// 		'user' 		=> 'USER',
// 		'pass' 		=> 'PASSWORD',
// 		'port' 		=> 'PORT NUMBER'
// );

/*
|--------------------------------------------------------------------------
| Connect to SQLite
|--------------------------------------------------------------------------
*/
// $config = array(
// 		'driver' 	=> 'sqlite',
// 		'host' 		=> './sites/coda.sqlite',
// 		'database'  => '',
// 		'user' 		=> '',
// 		'pass' 		=> '',
// 		'port' 		=> ''
// );