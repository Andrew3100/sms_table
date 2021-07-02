<?php
require_once 'libs/lib.php';
require_once 'libs/html_elements_lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;

$user = new user();
$user->unAuth_user();
echo "<script>window.location.replace('auth_form.php')</script>";