<?php
require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;
$table = array_keys($_GET)[0];
$id = array_keys($_GET)[1];
$fields = $DB->getTableFieldsName($table);

for ($i = 0; $i < count($fields); $i++) {
    if ($fields[$i]!='id' AND $fields[$i]!='author' AND $fields[$i]!='status') {
        $fieldss[] = $fields[$i];
    }
}

$obj = new stdClass();
for ($i = 0; $i < count($fieldss); $i++) {
    $obj->{$fieldss[$i]} = $_POST['name'.$i];
}
$id = $DB->update_recordById($table,$obj,$id);

$log->fixed('admin',"Обновление записи № $id  таблице $table");
echo "<script>window.location.replace('table.php?$table')</script>";
