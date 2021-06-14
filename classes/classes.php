<?php
//Класс для работы с таблицей
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

// Класс для работы с БД
class DB {
    public $db_host = 'localhost';
    public $db_user = 'root';
    public $db_password = 'root';
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

    function getRecordsForTableInterfaceArray($table,$where='',$fieldss = '*') {
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
    function reportToExcel($content,$headers) {
        require_once 'Excel/Classes/PHPExcel.php';
        $cells = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
        //Новый объект Excel
        $excel = new PHPExcel();
        //Определяем стартовую ячейку для формирования документа
        $excel->setActiveSheetIndex(0);

        for ($i = 0; $i <count($headers); $i++) {
            $excel->getActiveSheet()->setCellValue($cells[$i].'1',$headers[$i]);
        }

        for ($i = 0; $i < count($content); $i++) {
            $s = 1;
            $n = 1;
            for ($g = 0; $g < count($content[$i]); $g++) {
                $excel->getActiveSheet()->setCellValue($cells[$g].($i),$content[$i][$g]);
                $s++;
                $n++;
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="hello.xlsx');
        header('Cash-Control: max-age=0');
        $file = PHPExcel_IOFactory::createWriter($excel,'Excel2007');
        $file->save('php://output');
    }
    // метод вставляет запись в таблицу.
    // Запись передаётся в виде объекта, где свойства - поля таблицы
    // Возвращает идентификатор вставленной записи
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
        //считаем крайний ИД в таблице
        $last_id = $mysqli->query("SELECT MAX(`id`) FROM $table");

        $mysqli->query("INSERT INTO $table $string_fields VALUES $string_for_insert");
        // возвращаем вставленный ИД
        return (mysqli_fetch_assoc($last_id)["MAX(`id`)"]) + 1;
    }
    //Метод обновляет запись в таблице
    //Запись передаётся объектом, где свойства - поля БД
    //Для адресации записи передать ID этой записи
    function update_recordById($table,$object_upd,$id) {
        $mysqli = $this->setConnect();
        $keys = get_object_vars($object_upd);
        $keys1 = array_keys($keys);

        for ($i = 0; $i < count($keys); $i++) {
          $set =  $object_upd->{$keys1[$i]};
          $mysqli->query("UPDATE $table SET `{$keys1[$i]}` = '$set' WHERE id = $id");
        }
        return $id;
    }
    //метод удаляет данные из заданной таблицы
    //по идентификатору, который затем возвращает
    function deleteRecordById($table,$id) {
        $mysqli = $this->setConnect();
        $mysqli->query("DELETE FROM $table WHERE id = $id");
        return $id;
    }

}

class html_form {

    //Метод открывает форму. Параметры - файл обработки и метод ПД
    function openForm($action,$method='POST') {
        echo "<form action='$action' method='$method'>";
    }
    //Метод закрывает форму, можно передать цвет и текст кнопки. Если не передать, дефолтный цвет - success, текст - Отправить
    function closeForm($button_text="Отправить",$class='success') {
        echo "<button type='submit' class='btn btn-$class'>$button_text</button>";
    }

    //Метод выводит поле ввода по типу
    //Идентификатор используется для обозначения атрибутов name и for
    //Можно передать текст Bootstrap-метки для поля ввода. Если не передать, будет просто поле ввода
    //Можно указать ширину, если не указать, дефолт 600пкс
    function getFormByType($type,$id,$label='',$width=600) {
        include 'html/template.html';
        $width .= 'px';
        if ($label!='') {
            echo "<label for='$id' class='form-label'>$label</label>";
        }
        echo "<input name='name$id' type='$type' class='form-control' id='$id' style='width: $width;'>";
    }

}