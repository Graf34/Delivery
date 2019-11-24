<?php
$host = 'localhost'; // адрес сервера
$database = 'cities'; // имя базы данных
$user = 'root'; // имя пользователя
$password = ''; // пароль

//Подключение
$link = mysqli_connect($host, $user, $password);
//Создание базы данных и таблицы
$queryCreateBD = "CREATE DATABASE $database";
$queryCreateTable = "CREATE TABLE `cities`. `cities_table` ( `id` INT NOT NULL AUTO_INCREMENT , `city` VARCHAR(30) NOT NULL , `update_date` DATE NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;;";
mysqli_query($link, $queryCreateBD);
mysqli_query($link, $queryCreateTable);
//Закрыть подключение
mysqli_close($link);
?>