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

$block = $DB->getRecordsByConditionFetchAssoc('administration_table_link',"`link_get` = '$get'",'*');

foreach ($block as $blok) {
    $blocks = $blok['header'];
    $for_link = $blok['get'];
}

//интерфейсное имя таблицы
$table_named = $DB->getRecordsByConditionFetchAssoc('administration_table_link',"`link_get` = '$get'");
foreach ($table_named as $table_name1) {
    $table_name = $table_name1['linkname'];
}
$bread = [
    "index.php?main=1" => 'Главная',
    "index.php?data=1" => 'Работа с данными',
    "table_list.php/$for_link" => "$blocks",
    "$table_name"
];
$active = [
    '',
    '',
    '',
    'active'
];

//объекты
$table =     new html_table();
$bootstrap = new Bootstrap();

$bootstrap->GetHeader();
echo '<br><br>';
$bootstrap->getBreadcrumb($bread,$active);
echo '<br><br>';

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

for ($i=0; $i < count($content); $i++) {
    for ($g=0; $g < count($content[$i]); $g++) {
        if ($content[$i][$g] == NULL) {
            unset($content[$i][$g]);
        }
    }
}
$content = array_values($content);

$m_up_left = [
    "<a href='print_excel.php?$get'>Сохранить в Excel всю таблицу<img src='https://zappysys.com/images/ssis-powerpack/ssis-export-excel-file-task.png' style='width: 25px; height: 25px; margin-left: 10px;'></a>",

    "<form enctype='multipart/form-data' method='post' action='load.php?$get'>
        <label class='form-label' for='excel'>Загрузить данные из Excel</label>
        <input class='form-control' type='file' required name='excel' id='excel'>
        
        <button style='margin-top: 15px;' class='btn btn-sm btn-success' type='submit'>Загрузить</button>
        
        
    </form>",
    "<a class='btn btn-success' href='add.php?$get' style='border-radius: 100px'>Добавить одну запись +</a>"
];

$form_date = new html_form();
$form = "<p>Отчёт за период</p>";
$form .= $form_date->openForm('report_select.php','post');
$form .=$form_date->getFormByType('date',1,'Начало периода',150);
$form .=$form_date->getFormByType('date',2,'Конец периода',150).'<br>';
$form .=$form_date->closeForm('Отчёт','success');

$m_up_right = [
    "
    
    <p>$form</p>
    
    ",
];




$menu_html_up_left = $bootstrap->setListMenu($m_up_left);
$menu_html_up_right = $bootstrap->setListMenu($m_up_right);
$html_table = $table->printTable($table_name,$headers,$content);
$menu_html = $bootstrap->setListMenu($menu_list);
$html = [$menu_html_up_left,$menu_html_up_right];
$html2 = [$html_table];
echo $bootstrap->setContainer([6,6],$html,'fluid');
echo $bootstrap->setContainer([12],$html2,'fluid');