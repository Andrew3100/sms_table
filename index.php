<?php
require_once 'libs/html_elements_lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';

$obj = new stdClass();

$obj->firstname = 'лёха';
$obj->name = 'кислых';
$obj->lastname = 'Дмитриевич';
$obj->ban = 0;
$obj->password = 'analgladkov';
$obj->login = 'petre';
$obj->isadmin = 0;


for ($i = 0; $i < 120; $i++) {
    $DB->deleteRecordById('users',$i);
}