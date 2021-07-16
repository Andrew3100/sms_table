<?php

require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;
$table = array_keys($_GET)[0];

$headers = $DB->getInterfaceFields($table);
$content = $DB->getRecordsForTableInterfaceArray($table);


for ($i = 0; $i < count($content); $i++) {
    for ($g = 0; $g < count($content[$i]); $g++) {
        // 0 - поле id
        // 7 - поле поле автора
        // 8 - поле статуса записи
            unset($content[$i][0]);
            unset($content[$i][7]);
            unset($content[$i][8]);

    }
    $content_all[] = array_values($content[$i]);


}


$DB->reportToExcel($content_all,$headers);

