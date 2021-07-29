<?php

//Библиотека
require_once 'libs/lib.php';
//Классы
require_once 'classes/classes.php';
//База
require_once 'db/db_config.php';

$table = new html_table();

if (!isset($_GET['limit'])) {
    $limit = "Limit 100";
}
else {
    if ($_GET['limit'] == 'all') {
        $limit = '';
    }
    else {
        $limits = $_GET['limit'];
        $limit = "Limit $limits";
    }
}
$logs_data = $DB->getRecordsForTableInterfaceArray('logs','',"`id` DESC $limit",'id,event,date,time,username');
$headers = array(
  'Идентификатор',
  'Событие',
  'Дата',
  'Время',
  'Пользователь',
);


//pre($headers);
for ($i = 0; $i <= count($logs_data); $i++) {
    for ($g = 0; $g <= count($logs_data[$i]); $g++) {
        if ($logs_data[$i][$g] == NULL) {
            unset($logs_data[$i][$g]);
        }
    }
}
$logs_data = array_values($logs_data);
echo '<h6>Показать записей: 
<a href="/index.php?logs=1&limit=20">20</a>
<a href="/index.php?logs=1&limit=40">40</a>
<a href="/index.php?logs=1&limit=60">60</a>
<a href="/index.php?logs=1&limit=80">80</a>
<a href="/index.php?logs=1&limit=100">100</a>
<a href="/index.php?logs=1&limit=150">150</a>
<a href="/index.php?logs=1&limit=300">300</a>
<a href="/index.php?logs=1&limit=500">500</a>
<a href="/index.php?logs=1&limit=1000">1000</a>
<a href="/index.php?logs=1&limit=all">Все</a>
</h6>';
echo $table->printTable('Журнал логов',$headers,$logs_data);