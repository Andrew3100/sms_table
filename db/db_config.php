<?php
require_once 'classes/classes.php';
$DB = new DB();
$DB->db_param();
$log = new log();
return $DB;

