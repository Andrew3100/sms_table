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
    function setConnect() {
        $mysqli = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_base);
        if ($mysqli->connect_error) {
            echo "Ошибка подключения к базе данных";
        }
        return $mysqli;
    }
    function getRecordsByCondition($table,$where='') {
        $mysqli = $this->setConnect();
        $mysqli->set_charset("utf8");
        return $records = $mysqli->query("SELECT * FROM $table");
    }
}

