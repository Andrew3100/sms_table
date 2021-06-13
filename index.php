<?php
require_once 'libs/html_elements_lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';

$obj = new stdClass();

$obj->firstname = 'Фуников';
$obj->name = 'Андрей';
$obj->lastname = 'Дмитриевич';
$obj->ban = 0;
$obj->password = 'coronavirus';
$obj->login = 'Andre';
$obj->isadmin = 0;
echo $DB->insert_record('users',$obj);

