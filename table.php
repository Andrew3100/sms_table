<?php
//классы
require_once 'classes/classes.php';
//Библиотека
require_once 'libs/lib.php';
require_once 'db/db_config.php';
global $DB;
require_once 'html/template.html';

//вычисляем GET
$get = array_keys($_GET)[0];

//интерфейсное имя таблицы
$table_named = $DB->getRecordsByConditionFetchAssoc('administration_table_link',"`link_get` = '$get'");
foreach ($table_named as $table_name1) {
    $table_name = $table_name1['linkname'];
}
//объекты
$table =     new html_table();
$bootstrap = new Bootstrap();

$bootstrap->GetHeader();
echo '<br><br><br>';

$menu_list =
    [
        '<a href="index.php?data=1">Работа с данными</a>',
        '<a href="index.php?reports=1">Отчёты</a>',
        '<a href="index.php?import=1">Импорт данных</a>'
    ];

$content = $DB->getRecordsForTableInterfaceArray($get,'','*');
$headers = $DB->getTableFieldsName($get);


$actions = [
    "<a href='pdf.php?$get'>Сохранить  в Excel</a>",
    "<a href='excel.php?$get'>Сохранить  в PDF</a>",
    "<form enctype='multipart/form-data' method='post' action='load.php?$get'>
        <label class='form-label' for='excel'>Загрузить данные</label>
        <input class='form-control' type='file' required name='excel' id='excel'>
        
        <button style='margin-top: 15px;' class='btn btn-sm btn-success' type='submit'>Загрузить</button>
    </form>"
];



$html_table = $bootstrap->setListMenu($actions,400);

$html_table .= $table->printTable($table_name,$headers,$content);
$menu_html = $bootstrap->setListMenu($menu_list);
$html = [$menu_html,$html_table];
echo $bootstrap->setContainer([3,9],$html,'fluid');