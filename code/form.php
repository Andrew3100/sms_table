<?php
//доступ к данным из формы $_POST
//переменная инициализируется знаком доллара
//имя
$name = $_POST['name'];
//echo '<br>';
//почта
$mail = $_POST['mail'];
//echo '<br>';
//тел
$num = $_POST['num'];
//echo '<br>';
//тема
$theme = $_POST['theme'];

$db_host = "localhost"; //имя подключения
$db_user = "root"; // Логин БД
$db_password = ""; // Пароль БД
$db_base = 'test_db'; // Имя БД

$mysqli = new mysqli($db_host,$db_user,$db_password,$db_base);
$mysqli->set_charset("utf8");
if ($mysqli->connect_error) {
    echo "Ошибка подключения к базе данных";
}
//вставка данных
//$insert = $mysqli->query("INSERT INTO messages (`name`,`mail`,`num`,`theme`) VALUES ('$name','$mail','$num','$theme')");
//если не получается выполнять запрос, можно проверить при помощи print_r
//print_r('текст запроса');
//проверка успешности
/*if ($insert) {
    echo 'Данные помещены в БД';
}*/

$select = $mysqli->query("SELECT * FROM messages WHERE `mail` = '$mail' AND `deletes` = 1");
//print_r("SELECT * FROM messages WHERE `mail` = '$mail' AND `deletes` = 1");

echo '<table border="1">';
echo '<tr>';
echo '<th>Идентификатор</th>';
echo '<th>Имя</th>';
echo '<th>Почта</th>';
echo '<th>Номер телефона</th>';
echo '<th>Тема обращения</th>';
echo '</tr>';
while ($k = mysqli_fetch_assoc($select)) {
    //начало строки
    echo '<tr>';
    $id     =    $k['id'];
    $name   =    $k['name'];
    $mail   =    $k['mail'];
    $number =    $k['num'];
    $theme  =    $k['theme'];

    echo '<td>'.$id.'</td>'; //поля БД
    echo "<td>$name</td>"; //поля БД
    echo "<td>$mail</td>"; //поля БД
    echo "<td>$number</td>"; //поля БД
    echo "<td>$theme</td>"; //поля БД
    //конец строки
    echo '</tr>';
}
echo '</table>';
