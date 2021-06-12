<?php
require_once 'libs/html_elements_lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
echo '<div class="container">';
echo '<div class="row">';
echo '<div class="col">';
GetHeader();
echo '</div>';
echo '</div>';
echo '</div>';
exit();


$headers = $DB->getTableFieldsName('target_recomendes');
$records_array = $DB->GetRecordsForTableInterfaceArray('target_recomendes');

$table = new html_table();
echo '<div class="container">';
echo '<div class="row">';
echo '<div class="col">';
echo $table->printTable($headers,$records_array);
echo '</div>';
echo '</div>';
echo '</div>';