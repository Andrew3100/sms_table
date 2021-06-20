<?php
require_once 'libs/html_elements_lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
include 'html/template.html';

$DB = new DB;
$h = ['id','name','surname'];
$t = ['int','text','text'];


for ($i = 0; $i < 150; $i++) {
    $obj = new stdClass();

    $obj->name = 'Андрей';
    $obj->surname = "Петров";

    if ($DB->insert_record('MT',$obj)) {
        echo $i;
    }
}


