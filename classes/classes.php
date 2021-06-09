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