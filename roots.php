<?php

echo '<h3 style="text-align: center">Настройка прав доступа</h3>';

$users = $DB->getRecordsByConditionFetchAssoc('users');

$headers = $DB->getTableFieldsName('users','');
$content = $DB->getRecordsForTableInterfaceArray('users');

$table = new html_table();

echo $table->printTable('Действующие пользователи',$headers,$content);
