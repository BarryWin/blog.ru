<?php
$connection = mysqli_connect(
    $config['db']['server'],
    $config['db']['username'],
    $config['db']['password'],
    $config['db']['dbname']
);
if ($connection == false) {
    echo 'Нет подключения к базе данных!<br>';
    echo mysqli_connect_error();
    exit();
} ;
