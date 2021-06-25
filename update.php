
<?php
echo '<body style="background-color: #f3fbf3">';
require_once 'libs/html_elements_lib.php';
require_once 'libs/lib.php';
require_once 'classes/classes.php';
include 'db/db_config.php';
global $DB;

$table = array_values($_GET)[1];
$id    = array_values($_GET)[0] ;
$form  = new html_form();

$assoc = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`get_name` = '$table'",'*');

$datas = $DB->getRecordsByConditionFetchAssoc($table,"`id` = '$id'");


foreach ($assoc as $arr) {
    $fetch[] = $arr['type_name'];
    $fetch1[] = $arr['descriptor_n'];
}
echo $form->openForm("upd_script.php?$table&$id",'post');
echo '<div style="position: absolute; left: 50%; top: 50%;-webkit-transform: translate(-50%, -50%);-moz-transform: translate(-50%, -50%);-ms-transform: translate(-50%, -50%);-o-transform: translate(-50%, -50%);transform: translate(-50%, -50%);>';


for ($i = 0; $i < count($fetch); $i++) {
    echo $form->getFormByType($fetch[$i],$i,$fetch1[$i],500);
}
echo '<br>';
$form->closeForm('Обновить','success');
echo '</div>';
echo '</body>';



