<?php

require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;
$id    = $_GET['del'];
$table = $_GET['table'];

$DB->deleteRecordById($table,$id);
echo "<script>window.location.replace('table.php?$table')</script>";