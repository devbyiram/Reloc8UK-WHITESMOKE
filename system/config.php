<?php
error_reporting(E_ALL & ~E_DEPRECATED & ~E_WARNING);
session_start();
date_default_timezone_set("Europe/London");
// Require functions.php
// Database Credentials
define('MYSQL_HOST', 'localhost');
define('MYSQL_USER', 'root');
define('MYSQL_DB', 'freelanceproject');
define('MYSQL_PASS', 'root');

require('connect.php');
require('functions.php');
require('propertyfunctions.php');
require('dashboardfunctions.php');
