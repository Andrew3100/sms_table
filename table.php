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

echo $bootstrap->GetHeader();
echo '<br><br><br>';

$menu_list =
    [
        '<a href="index.php?data=1">Работа с данными</a>',
        '<a href="index.php?reports=1">Отчёты по выборочным данным</a>',
        '<a href="index.php?import=1">Логи</a>',
        '<a href="index.php?roots=1">Права доступа</a>',
        '<a href="index.php?create_user=1">Создать пользователя</a>',
        '<a href="index.php?create=1">Создать раздел</a>'
    ];

$fields = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`get_name` = '$get'");

foreach ($fields as $field) {
    //готовые заголовки для таблицы
    $headers[] = $field['descriptor_n'];
}

$fields = $DB->getTableFieldsName($get);

for ($i = 0; $i < count($fields); $i++) {
    if ($fields[$i] != 'status' AND $fields[$i] != 'author') {
        $fields_for_interface[] = $fields[$i];
    }
}

$fields_for_interface = implode($fields_for_interface,',');

$content = $DB->getRecordsForTableInterfaceArray($get,'',$fields_for_interface);

$actions = [
    "<a href='pdf.php?$get'>Сохранить  в Excel<img src='https://zappysys.com/images/ssis-powerpack/ssis-export-excel-file-task.png' style='width: 25px; height: 25px; margin-left: 10px;'></a>",
    "<a href='excel.php?$get'>Сохранить  в PDF<img src='https://ukdeafsport.org.uk/wp-content/uploads/2016/10/PDF-Form-Logo-WEB-1024x1024.jpg' style='width: 25px; height: 25px; margin-left: 10px;'></a>",
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