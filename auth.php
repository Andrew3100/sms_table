<?php

require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;

$l = $_POST['namelogin'];
$p = $_POST['namepass'];

$user = new user();

$user->authUser($l,$p);
