<?php
$host = 'localhost'; // адрес сервера
$database = 'cities'; // имя базы данных
$user = 'root'; // имя пользователя
$password = ''; // пароль

$link = mysqli_connect($host, $user, $password, $database)
or die("Ошибка " . mysqli_error($link));


$queryDate = "SELECT update_date FROM cities_table";//Получение даты обнавления списка
$resultDate = mysqli_query($link, $queryDate) or die("Ошибка " . mysqli_error($link));

$rows = mysqli_num_rows($resultDate); // количество полученных строк
$row = mysqli_fetch_row($resultDate);
if ($rows == 0 or $row[0] != date('o' . '-' . 'm' . '-' . 'd')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, 'exercise.develop.maximaster.ru/service/city/');
    $result = curl_exec($ch);
    curl_close($ch);
    echo($row[0]);

    $obj = json_decode($result);

    $query = "TRUNCATE TABLE `cities_table`";//Очистить таблицу
    $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    //Заполнение новыми данными
    for ($x = 0; $x < count($obj); $x++) {
        $query = "INSERT INTO `cities_table` (`id`, `city`, `update_date`) VALUES (NULL, '$obj[$x]', CURRENT_DATE());";
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
    }
}

if (!empty($_POST['cities'])) {

    $city = $_POST['cities'];
    $weight = $_POST['weight'];
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, 'exercise.develop.maximaster.ru/service/delivery/?city=' . $city . '&weight=' . $weight);
    $price = json_decode(curl_exec($ch));
    curl_close($ch);


}
?>

<html>
<head>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<form method="POST" action="index.php" id="form">
    <select name=cities>
        <option value="Москва">Москва</option>
        <?php
        $query = 'SELECT city FROM cities_table WHERE city != "Москва" ';
        $result = mysqli_query($link, $query) or die("Ошибка " . mysqli_error($link));
        $rows = mysqli_num_rows($result); // количество полученных строк

        for ($i = 1; $i < $rows; ++$i) {
            $row = mysqli_fetch_row($result);
            echo '<option value=' . $row[0] . '>' . $row[0] . '</option>';
        }
        ?>
    </select>
    <br/>
    <input type="text" placeholder="Вес, кг" name="weight">
    <br/>
    <input type="button" value="Отправить" onclick="document.getElementById('form').submit();">

</form>
<?php
if ($price->status == "error") {
    $color = "#FF0000";
} else {
    $color = "#000000";
}
echo('<p style=" color:' . $color . ';">' . $price->message . "</p>");
?>

</body>
</html>



