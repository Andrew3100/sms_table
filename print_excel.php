<?php

require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;
$table = array_keys($_GET)[0];

$headers = $DB->getInterfaceFields($table);
$content = $DB->getRecordsForTableInterfaceArray($table);

$DB->reportToExcel($content,$headers);

