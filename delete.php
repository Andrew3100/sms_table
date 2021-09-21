<?php

require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;
$id    = $_GET['del'];
$table = $_GET['table'];
$u = new stdClass();
$u->status = 0;
if ($DB->update_recordById($table,$u,$id,2))
{
    echo "<script>window.location.replace('table.php?$table')</script>";
}
else {
    echo 'Произошла ошибка при удалении записи';
}

