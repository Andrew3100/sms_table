<?php

echo '<h3 style="text-align: center">Настройка прав доступа</h3>';

$users = $DB->getRecordsByConditionFetchAssoc('users');

$headers = $DB->getTableFieldsName();
