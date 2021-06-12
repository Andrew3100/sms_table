<?php

class html_table {
function printTable($headers, $content) {
include 'html/template.html';
$table = '<table class="table table-dark table-bordered">';
    //цикл по заголовкам

    for ($i = 0; $i < count($headers); $i++) {
    $table .= "<td>{$headers[$i]}</td>";
    }

    //цикл по контенту
    for ($i = 0; $i < count($content); $i++) {
    $table .= '<tr>';

        for ($g = 0; $g < count($content[$i]); $g++) {
        $table .= "<td>{$content[$i][$g]}</td>";
        }
        $table .= '</tr>';
    }
    $table .= '</table>';

return $table;
}
}

class DB {
    public $db_host = 'localhost';
    public $db_user = 'root';
    public $db_password = '';
    public $db_base = 'jc_database';
    //метод устанавливает соединение с БД
    function setConnect() {
        $mysqli = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_base);
        $mysqli->set_charset("utf8");
        if ($mysqli->connect_error) {
            echo "Ошибка подключения к базе данных";
        }
        return $mysqli;
    }
    //метод возврашает ассоциативный массив
    //данных из заданной таблицы.
    //Можно передать условие отбора записей и отбираемые поля в строке через запятую
    //Если отбираемые поля и условие не переданы, то выберутся все записи по всем полям
    function getRecordsByConditionFetchAssoc($table,$where='',$fields = '*') {
        $mysqli = $this->setConnect();

        if ($where!='') {
            $condition = "WHERE $where";
        }
        else {
            $condition = '';
        }
        return $records = $mysqli->query("SELECT $fields FROM $table $condition");
    }
    // Метод возвращает массив полей заданной таблицы
    function getTableFieldsName($table) {
        $mysqli = $this->setConnect();
        $fields = $mysqli->query("SHOW COLUMNS FROM $table");
        foreach ($fields as $fieldss) {
            $fields_list[] = $fieldss['Field'];
        }
        return $fields_list;
    }

    function GetRecordsForTableInterfaceArray($table,$where='',$fieldss = '*') {
        $mysqli = $this->setConnect();
        // получаем поля в виде массива
        $fields = $this->getTableFieldsName($table);


        if ($where!='') {
            $condition = "WHERE $where";
        }
        else {
            $condition = '';
        }
        $records = $mysqli->query("SELECT $fieldss FROM $table $condition");
        //создаём из записей обычный массив
        foreach ($records as $records1) {
            for ($i = 0; $i < count($fields); $i++) {
                $array[] = $records1[$fields[$i]];
            }
            $array_result[] = $array;
            unset($array);
        }
        return $array_result;
    }
    //метод формирует excel файл из таблицы

    function reportToExcel($content,$headers) {
        require_once 'Excel/Classes/PHPExcel.php';
        // Создаем объект класса PHPExcel
        $xls = new PHPExcel();
        // Устанавливаем индекс активного листа
        $xls->setActiveSheetIndex(0);
        // Получаем активный лист
        $sheet = $xls->getActiveSheet();
        // Подписываем лист
        $sheet->setTitle('Очтёт');
        $symbols = ["A","B","C","D"];
        for ($i = 0; $i < count($headers); $i++) {
            $sheet->setCellValue();
        }

    }

}

