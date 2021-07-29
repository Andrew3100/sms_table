<?php
//классы
require_once 'classes/classes.php';
//Библиотека
require_once 'libs/lib.php';
require_once 'db/db_config.php';
require_once 'Excel/Classes/PHPExcel.php';
global $DB;
$use = new user();
$use->setUserData();

//имя таблицы
$table_name = array_keys($_GET)[0];
//массив латинских букв для обращения к ячейкам
$splinters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R',  'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
//получаем файл из глобального массива
$excel = PHPExcel_IOFactory::load($_FILES['excel']['tmp_name']);
//счётчик
$g = 0;
//проходимся по 1 строке и считываем заголовки в ячейках
while ($value = $excel->getActiveSheet()->getCell($splinters[$g].'1')->getValue()!="") {
    //получаем заголовки для определения того, верный ли файл был загружен
    $headers_from_excel[] = $excel->getActiveSheet()->getCell($splinters[$g].'1')->getValue();
    //задействованное лат. буквы пишем в массив (конец массива - конец таблицы с правого края)
    $using_splinters[] = $splinters[$g];
    $g++;
}
//заголовки для сравнения
$headers_from_database_for_compares = $DB->getRecordsByConditionFetchAssoc('bsu_form_data',"`get_name` = '$table_name'",'*');

foreach ($headers_from_database_for_compares as $headers_from_database_for_compar) {
    $headers_from_database_for_compare[] = $headers_from_database_for_compar['descriptor_n'];
}

//если массив расхождений не пустой - пользователь выбрал неверный файл для загрузки
if (array_diff($headers_from_database_for_compare,$headers_from_excel) != NULL) {
    exit('<h1 style="text-align: center; color: red; margin-top: 120px;">Неверный файл для загрузки. Выберите верный файл и повторите попытку. <a href="/table.php?'.$table_name.'">Назад</a></h1>');
}


//получаем данные о полях БД, которые надо пополнять записями
$headers_from_database = $DB->getRecordsForTableInterfaceArray('bsu_form_data',"`get_name` = '$table_name'",'fn');

for ($i = 0; $i < count($headers_from_database); $i++) {
    for ($g = 0; $g < count($headers_from_database[$i]); $g++) {
        if ($headers_from_database[$i][$g] != NULL) {
            $databases_fields[] = $headers_from_database[$i][$g];
        }
    }
}

//Проходимся по всем записям
$i=2;
while ($value = $excel->getActiveSheet()->getCell('A'.$i)->getValue()!="") {
    //объект для вставки записи в БД
    $insert = new stdClass();
    for ($k = 1; $k <= count($databases_fields); $k++) {
        //формируем объект - свойство элемент массива $databases_fields (оно же - поле БД)
        $insert->{$databases_fields[$k-1]} = $excel->getActiveSheet()->getCell($using_splinters[$k-1].$i)->getValue();
    }
    //Автор и признак удаления записи
    $insert->author = $use->name;
    $insert->status = 1;
    //вставляем запись
    $DB->insert_record($table_name,$insert);
    $i++;
}
//лог
$log = new log();
$log->fixed($use->name,'Импорт данных из файла Excel');
echo '<script>`Файл загружен. Порсле нажатия кнопки ОК загрузится обновлённая таблица`</script>';
echo "<script>window.location.replace('/table.php?$table_name');</script>";