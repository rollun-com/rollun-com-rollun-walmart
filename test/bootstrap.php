<?php
global $argv;

use PHPUnit\Framework\Error\Deprecated;

error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);
Deprecated::$enabled = false;

// Change to the project root, to simplify resolving paths
chdir(dirname(__DIR__));

// start session
session_start();

// load container
$container = require 'config/container.php';

if (getenv("APP_ENV") === 'prod') {
    echo "You cannot start test if environment var APP_ENV not set in dev!";
    exit(1);
}