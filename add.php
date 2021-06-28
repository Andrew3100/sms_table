<?php
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;

$table = array_keys($_GET)[0];

$form  = new html_form();

$assoc = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`get_name` = '$table'",'*');



$form->openForm("add_script.php?$table",'post');
echo '<div style="position: absolute; left: 50%; top: 50%;-webkit-transform: translate(-50%, -50%);-moz-transform: translate(-50%, -50%);-ms-transform: translate(-50%, -50%);-o-transform: translate(-50%, -50%);transform: translate(-50%, -50%);>';
$i = 0;
foreach ($assoc as $arr) {
    $fetch[] = $arr['type_name'];
    $fetch1[] = $arr['descriptor_n'];
    echo $form->getFormByType($fetch[$i],$i,$fetch1[$i],500);
    $i++;
}
echo '<br>';
$form->closeForm('Добавить','success');
echo '</div>';






