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
    //метод формирует excel файл из таблицы и заголовков

    function reportToExcel() {
        require_once 'Excel/Classes/PHPExcel.php';

        //Новый документ Excel
        $excel = new PHPExcel();
        //Определяем стартовую ячейку для формирования документа
        $excel->setActiveSheetIndex(0);

        $excel->getActiveSheet()->setCellValue('A1','привет');


        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="file.xlsx"');


        header ('Expires: '.gmdate('D, d M Y H:i:s').' GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public');

        $objWriter = \PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('file.xlsx');
    }
    function insert_record($table,$record_object) {
        $mysqli = $this->setConnect();
        $keys = get_object_vars($record_object);
        $keys1 = array_keys($keys);

        $string_fields = '(`';
        for ($i = 0; $i < count($keys); $i++) {
            $string_fields .= $keys1[$i];
            $string_fields .= '`';
            if ($i!=count($keys)-1) {
                $string_fields .= ', ';
                $string_fields .= '`';
            }
        }
        $string_fields .= ')';
        
        $string_for_insert = "('";
        for ($i = 0; $i < count($keys); $i++) {
            $string_for_insert .= ($record_object->{$keys1[$i]});
            $string_for_insert .= "'";
            if ($i!=count($keys)-1) {
                $string_for_insert .= ', ';
                $string_for_insert .= "'";
            }
        }
        $string_for_insert .= ')';
        $last_id = $mysqli->query("SELECT MAX(`id`) FROM $table");

        $inserted = $mysqli->query("INSERT INTO $table $string_fields VALUES $string_for_insert");
        return (mysqli_fetch_assoc($last_id)["MAX(`id`)"]) + 1;
    }
}

