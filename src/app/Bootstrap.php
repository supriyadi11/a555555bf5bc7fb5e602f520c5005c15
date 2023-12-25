<?php

use App\Logger;
use App\Request;
include __DIR__ . '/database/boot.php';

$logger = new Logger();
$request = new Request();
include __DIR__ . "/../app/routes/api.php";
session_start();
